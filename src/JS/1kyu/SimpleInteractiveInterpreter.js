Interpreter.prototype = {
    input: function(expr) {
        let tokens = this.tokenize(expr);
        let tree   = this.parse(tokens);
        let result = this.interpret(tree);
        return result;
    },
    interpret: function(tree) {
        switch(tree.type) {
            case "operator":   return this.interpretOperator(tree);
            case "number":     return this.interpretNumber(tree);
            case "assignment": return this.interpretAssignment(tree);
            case "identifier": return this.interpretIdentifier(tree);
            case "function":   return this.interpretFunction(tree);
            case "fnCall":     return this.interpretFnCall(tree);
            case "container":  return this.interpretContainer(tree);
            case "noop":       return this.interpretNoop(tree);
            default:
                throw "What type is " + JSON.stringify(tree);
        }
    },
    interpretNumber: function(number) {
        return parseFloat(number.value);
    },
    interpretAssignment: function(assignment) {
        if(assignment.name in this.functions)
            throw "Variable name collides with function name: " + assignment.name;
        let value = this.interpret(assignment.value);
        this.letiables[assignment.name] = value;
        return value;
    },
    interpretIdentifier: function(identifier) {
        if(identifier.value in this.letiables)
            return this.letiables[identifier.value];
        throw "Missing identifier: " + identifier.value;
    },
    interpretFunction: function(fn) {
        if(fn.name in this.letiables)
            throw "Function name collides with letiable name: " + fn.name;
        this.functions[fn.name] = fn;
        return "";
    },
    interpretFnCall: function(fnCall) {
        let that = this;
        let fn   = this.functions[fnCall.name];
        let args = fnCall.args.reduce(function(args, pair) {
            args[pair[0]] = that.interpret(pair[1]);
            return args;
        }, Object.create(this.letiables));
        let oldVars = this.letiables;
        this.letiables = args;
        let result = this.interpret(fn.body);
        this.letiables = oldVars;
        return result;
    },
    interpretOperator: function(tree) {
        let left  = this.interpret(tree.left);
        let right = this.interpret(tree.right);
        switch(tree.operator) {
            case "+": return left + right;
            case "*": return left * right;
            case "-": return left - right;
            case "/": return left / right;
            case "%": return left % right;
            default:
                throw "What operator is in here? " + JSON.stringify(tree);
        }
    },
    interpretNoop: function(noop) {
        return "";
    },
    interpretContainer: function(container) {
        return this.interpret(container.child);
    },
    tokenize: function(program) {
        if (program === "")
            return [];
        let regex = /\s*(=>|[-+*\/\%=\(\)]|[A-Za-z_][A-Za-z0-9_]*|[0-9]*\.?[0-9]+)\s*/g;
        return program.split(regex).filter(function (s) { return !s.match(/^\s*$/); });
    },
    parse: function(tokens) {
        let parsed = new Parser(this.functions, tokens).parse();
        if(tokens.length !== 0)
            throw "Extra tokens: " + i(tokens);
        return parsed;
    },
};


function Parser(functions, tokens) {
    this.functions = functions;
    this.tokens  = tokens;
}
Parser.prototype = {
    parse: function() {
        if(this.noInput())
            return this.noop();
        if(this.isFunction())
            return this.parseFn();
        else
            return this.parseExpr();
    },
    parseFn: function() {
        this.shift(); // fn
        let name = this.tokens.shift();
        let args = this.parseFnArgs();
        this.shift(); // =>
        let body = this.parseExpr();
        this.validateIdentifiers(args, body);
        let fn   =  {
            type: "function",
            name: name,
            args: args,
            body: body,
        };
        return fn;
    },
    shift: function() {
        return this.tokens.shift();
    },
    parseExpr: function() {
        let leftExpr  = null;

        if(this.tokens.length === 0)
            throw "omg!";

        if(this.isAssignment()) {
            leftExpr = this.parseAssignment();
        } else if(this.isNumber()) {
            leftExpr = this.parseNumber();
        } else if(this.isFnCall()) {
            leftExpr = this.parseFnCall();
        } else if(this.isIdentifier()) {
            leftExpr = this.parseIdentifier();
        } else if(this.opensContainer()) {
            leftExpr = this.parseContainer();
        } else if(this.isFunction()) {
            leftExpr = this.parseFn();
        } else if(this.closesContainer()) {
            throw "WHAT THE FUCK IS: " + JSON.stringify(this.tokens);
        }

        if(this.tokens.length === 0)
            return leftExpr;

        if(!this.isOperator())
            return leftExpr;

        let operator  = this.shift();
        let rightExpr = this.parseExpr();
        if((rightExpr.type !== 'operator') || (!this.shouldSwapOperators(operator, rightExpr.operator)))
            return {
                type:     "operator",
                operator: operator,
                left:     leftExpr,
                right:    rightExpr,
            };

        rightExpr.left = {
            type:     "operator",
            operator: operator,
            left:     leftExpr,
            right:    rightExpr.left,
        }
        return rightExpr;
    },
    noInput: function() {
        return this.tokens.length === 0;
    },
    noop: function() {
        return {type: 'noop'};
    },
    isFnCall: function() {
        return this.isIdentifier() && this.functions[this.tokens[0]];
    },
    isNumber: function() {
        return this.tokens[0].match(/^[0-9][\.0-9]*$/);
    },
    isOperator: function() {
        let t = this.tokens[0] ;
        return t === '+' ||
            t === '-' ||
            t === '*' ||
            t === '/' ||
            t === '%';
    },
    shouldSwapOperators: function(leftOp, rightOp) {
        return leftOp  === '*' || leftOp  === '/' || leftOp === '%' ||
            rightOp === '+' || rightOp === '-';
    },
    isIdentifier: function() {
        return this.tokens[0].match(/^[a-zA-Z][_a-zA-Z0-9]*$/);
    },
    isAssignment: function() {
        return this.isIdentifier() && this.tokens[1] === '=';
    },
    opensContainer: function() {
        return this.tokens[0][0] === '(';
    },
    closesContainer: function() {
        return this.tokens[0][0] === ')';
    },
    isFunction: function() {
        return this.tokens[0] === 'fn';
    },
    parseNumber: function() {
        return { type: "number", value: this.tokens.shift() };
    },
    parseIdentifier: function() {
        return { type: "identifier", value: this.tokens.shift() };
    },
    parseAssignment: function() {
        let name = this.parseIdentifier().value;
        this.shift(); // =
        let value = this.parseExpr();
        return { type: "assignment", name: name, value: value };
    },
    parseFnCall: function() {
        let that = this;
        let name = this.tokens.shift();
        let fn   = this.functions[name];
        let args = fn.args.map(function(name) {
            if(that.tokens.length === 0)
                throw "Too few arguments!";
            return [name, that.parse()];
        });
        return {
            type: "fnCall",
            name: name,
            args: args,
        };
    },
    parseContainer: function() {
        this.shift(); // (
        let expr = this.parseExpr();
        this.shift(); // )
        return {type: 'container', child: expr};
    },
    parseFnArgs: function() {
        let args = [];
        while(this.tokens[0] !== "=>")
            args.push(this.tokens.shift());
        if(this.containsDuplicates(args))
            throw "Duplicate argument names";
        return args;
    },
    containsDuplicates: function(array) {
        for(let i=0; i < array.length; ++i)
            for(let j=i+1; j < array.length; ++j)
                if(array[i] === array[j])
                    return true;
        return false;
    },
    validateIdentifiers: function(names, tree) {
        let used = this.letNames(tree);
        used.forEach(function(name) {
            if(-1 === names.indexOf(name))
                throw "Unknown identifier: " + name;
        });
    },
    letNames: function(tree) {
        switch(tree.type) {
            case "operator":   return this.letNames(tree.left).concat(this.letNames(tree.right));
            case "number":     return [];
            case "assignment": return this.letNames(tree.value);
            case "identifier": return [tree.value];
            case "function":   return [];
            case "fnCall":
                let all = [];
                args.forEach(function(crnt) { all = all.concat(crnt); });
                return all;
            case "container":  return this.letNames(tree.child);
            case "noop":       return [];
            default:
                throw "What type is " + JSON.stringify(tree);
        }
    }
};

function Interpreter() {
    this.functions = {};
    this.letiables = {};
}

function i(obj) {
    return JSON.stringify(obj);
}

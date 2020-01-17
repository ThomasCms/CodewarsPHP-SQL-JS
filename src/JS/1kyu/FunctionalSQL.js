const groupByFn = (data, ...fns) => {
    let memo = {};
    let fn = fns.shift();
    let result;
    for(let i=0;i<data.length;i++){
        if (memo.hasOwnProperty(fn(data[i]))) {
            memo[fn(data[i])].push(data[i])
        } else {
            memo[fn(data[i])] = [data[i]]
        }
    }
    if (fns.length > 0) {
        result = Object.entries(memo).map(([groupName, toGroup],i) => {
            if (groupName && Number.isInteger(+(groupName))) {
                groupName = +groupName;
            }
            const temp = groupByFn(toGroup, ...fns);
            return [groupName, temp]
        });
    } else {
        result = Object.entries(memo).map(([groupName, toGroup],i) => {
            if (groupName && Number.isInteger(+(groupName))) {
                return [+groupName, toGroup]
            }
            return [groupName, toGroup]
        });
    }

    return result;
};
const checkForErrors = (stack, called) => {
    let onlyOnce = ['select', 'from', 'orderby', 'groupby', 'from'];
    onlyOnce.forEach(fn => {
        let flat = [...stack].map(([fn, ex]) => fn);
        let i = flat.indexOf(fn);
        if (called > 1) {
            throw new Error(`Duplicate ${'from'.toUpperCase()}`)
        }
        if (i >= 0) {
            let ni = flat.slice(i+1).indexOf(fn);
            if (ni >= 0) {
                throw new Error(`Duplicate ${fn.toUpperCase()}`)
            }
        }
    })
};
class Query {
    constructor() {
        this.stack = [];
        this.source = [];
        this.join = [];
        this.joined = false;
        this.called = 0;
    }
    select(...fns) {
        this.stack.push(['select', ...fns]);
        return this;
    }
    selectExecute(...fns) {
        if (this.joined === false) {
            fns.forEach(fn => {
                this.source = fn ? this.source.map(el => {
                    return fn(el)
                }) : this.source;
            })
        } else {
            fns.forEach(fn => {
                this.join = this.join.map(el => {
                    return fn(el)
                });
            })
        }
        return this;
    }
    from(source, r) {
        this.source = source;
        let rest = r || [];
        this.called += 1;
        for (let i = 0; i <= source.length-1; i++) {
            for(let j = 0; j <= rest.length-1; j++) {
                this.join.push([source[i],rest[j]])
            }
        }
        r ? this.joined = true : null;
        return this;
    }
    where(...fns) {
        this.stack.unshift(['where', ...fns]);
        return this;
    }
    whereExecute(...fns) {
        this.joined === false ? this.source = this.source.filter(el => {
            return fns.some(fn => fn(el))
        }) : null;
        this.join = this.join.filter(el => {
            return fns.some(fn => fn(el))
        });
        return this;
    }
    groupBy(...fn){
        this.stack.push(['groupby', ...fn]);
        return this;
    }
    groupExecute(...fns){
        let result = groupByFn(this.source, ...fns);
        this.source = result;
    }
    orderBy(...fns) {
        this.stack.push(['orderby', ...fns]);
        return this;
    }
    orderByExecute(...fns) {
        this.source = this.source.slice().sort(fns[0]);
    }
    having(...fns) {
        this.stack.push(['having', ...fns]);
        return this;
    }
    havingExecute(...fns){
        fns.forEach(fn => {
            this.source = this.source.filter(s => fn(s))
        });
    }
    execute() {

        let map = {
            'where': this.whereExecute,
            'groupby': this.groupExecute,
            'select': this.selectExecute,
            'orderby': this.orderByExecute,
            'having': this.havingExecute,
        }
        let order = {
            'where': 1,
            'groupby': 2,
            'orderby': 3,
            'having': 4,
            'select': 5,
        }
        if(this.source == []){
            return []
        }
        let err = checkForErrors(this.stack, this.called);
        if(err){
            throw new Error(err)
        }
        this.stack.sort((a,b) => order[a[0]] - order[b[0]])
        this.stack.forEach(([fn, ...fns]) => {
            return fns ? map[fn].call(this, ...fns) : map[fn].call(this);
        });

        return this.joined === false ? this.source : this.join;
    }
}

let query = () => new Query();

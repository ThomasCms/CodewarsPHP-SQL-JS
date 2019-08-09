function loop_size(node){
    let nodes = [], n = node;

    while (nodes.indexOf(n) === -1) {
        nodes.push(n);
        n = n.getNext();
    }

    return nodes.length - nodes.indexOf(n);
}

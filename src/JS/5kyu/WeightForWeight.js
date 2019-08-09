function orderWeight(strng) {
    let sum = (str)=>str.split('').reduce((sum,el)=>(sum+(+el)),0);
    function comp(a,b){
        let sumA = sum(a);
        let sumB = sum(b);
        return sumA === sumB ? a.localeCompare(b) : sumA - sumB;
    }
    return strng.split(' ').sort(comp).join(' ');
}

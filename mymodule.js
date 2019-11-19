function sortString(string) {
        return string.split('').sort().join('');
    }

module.exports.hello = function (stringA, stringB){
	
	 if (stringA.length !== stringB.length) {
            return false.toString();
        }

        let arrB = stringB.split("")

        for (let char of stringA ){ 
            if (!arrB.includes(char)) {
                return false
                break;
            } else {
                arrB.splice(arrB.indexOf(char), 1)
            }
        }

        return true.toString()
}
;

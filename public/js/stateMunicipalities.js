
function Municipalities(State){
    try {
        if(typeof State === 'string'){
            if(MUNICIPALITIES[State]){                    
                return MUNICIPALITIES[State];
            }else{
                return '*** Solo Valido para Estados Venezolanos ***'
            }
        } else{
            return '**** Especifique un String con el valor del Estado a Consultar ****';
        }
    } catch (error) {
        console.error(error);
    }
}

function State(){
    try {
        return STATE['State'];
    } catch (error) {
        console.error(error);
    }
}
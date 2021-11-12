/*
* cameilCase
* el primer carácter de la cadena convertido en minúsculas y 
* otros caracteres después del espacio se convertirán en mayúsculas.
*/

/*
String.prototype.toCamelCase = function() {
    return this.replace(/^([A-Z])|\s(\w)/g, function(match, p1, p2, offset) {
        if (p2) return p2.toUpperCase();
        return p1.toLowerCase();        
    });
};
'MifamiliaClass bien'.toCamelCase()  // -> mifamiliaClassBien
*/

import camelCase from 'lodash/camelCase'

// Aqui podemos almacenar en la variable un contexto con todos los archivos en esta carpeta
// terminando con `.js`.
// guardaremos todos los js

const files = require.context('.', false, /\.js$/)
const modules = {}
//Recorremos los archivos js
files.keys().forEach(fileName => {
  if (fileName ==='./index.js') return
   // filtra las paradas completas y la extensión 
   // y devuelve un nombre de camello para el archivo
  const moduleName=camelCase(
    fileName.replace(/(\.\/|\.js)/g,'')
  );
  // creamos un objeto dinámico con todos los módulos
  modules[moduleName] = {
      // agregamos el espacio de nombres aquí
      namespaced : true,
      ...files(fileName).default
      // si ha exportado el objeto con nombre en el archivo del módulo `js`
      // por ejemplo, export const name = {};
      // descomenta esta línea y comenta lo anterior
      // ... requireModule (fileName) [moduleName]
  }

});
export default modules
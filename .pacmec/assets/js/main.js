/*
window.onload = () => {
  'use strict';

  if ('serviceWorker' in navigator) {
    navigator.serviceWorker
             .register('./sw.js');
  }
}
*/
try {
  console.log("main RUN.")
} catch (e) {

} finally {
  console.log("main Finally.")
}

let eagleRoot = document.getElementById('eagle-app-form');
let show_product_code = eagleRoot.dataset.showProductCode || 0;

const height = 300;
const domain = 'https://promotion.listvipvn.com';
let sheet = document.createElement('style');
sheet.innerHTML = "" +
    ".eagle-loading {\n" +
    "   position: relative;\n" +
    "   height:" + height + "px" +
    "}\n" +
    ".eagle-loading:after {\n" +
    "     content: \"\";\n" +
    "     border: 10px solid #f3f3f3;\n" +
    "     border-top: 10px solid #3498db;\n" +
    "     border-bottom: 10px solid #3498db;\n" +
    "     position: absolute;\n" +
    "     z-index: 9999;\n" +
    "     top: 0;\n" +
    "     left: 0;\n" +
    "     right: 0;\n" +
    "     bottom: 0;\n" +
    "     border-radius: 50%;\n" +
    "     width: 60px;\n" +
    "     height: 60px;\n" +
    "     animation: spin 2s linear infinite;\n" +
    "     margin: auto;\n" +
    "     \n" +
    "}\n" +
    ".eagle-loading:before {\n" +
    "    content: \"\";\n" +
    "    background: #fff;\n" +
    "     top: 0;\n" +
    "     left: 0;\n" +
    "     right: 0;\n" +
    "     bottom: 0;\n" +
    "     z-index: 9998;\n" +
    "     position: absolute;\n" +
    "}" +
    "@keyframes spin {\n" +
    "  0% { transform: rotate(0deg); }\n" +
    "  100% { transform: rotate(360deg); }\n" +
    "}";
document.body.appendChild(sheet);
eagleRoot.setAttribute('class', 'eagle-loading');
let e_ifrm = document.createElement('iframe');
e_ifrm.setAttribute('id', 'iframe-eagle');
e_ifrm.style.cssText = 'border: none;width: 100%;height: ' + height+ 'px;';
e_ifrm.onload = function() {
    let ifr = document.getElementById('iframe-eagle').contentWindow;
    ifr.postMessage(
        JSON.stringify({
            event: 'resize'
        }), '*');
};
eagleRoot.appendChild(e_ifrm);
let url = domain + '/customer-register';
if(show_product_code){
    url += "?id=" + show_product_code;
}
e_ifrm.setAttribute('src', url);
window.addEventListener("message", receiveMessage, false);
function receiveMessage(event) {
    if (event.origin !== null && domain.indexOf(event.origin) === -1) {
        return;
    }
    if(event.data && typeof event.data === "string") {
        eagleRoot.setAttribute('class', ' ');
        let object = JSON.parse(event.data);
        if (object.event === 'resize') {
            e_ifrm.style.height = object.height + 'px';
        }
    }
}

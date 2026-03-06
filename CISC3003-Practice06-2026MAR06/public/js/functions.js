/* define functions here */

function calculateTotal(quantity, price) {
    return quantity * price;
}

function outputCurrency(num) {
    return "$" + num.toFixed(2);
}

function calculateTax(subtotal, rate) {
    return subtotal * rate;
}

function calculateShipping(subtotal, threshold) {
    if (subtotal > threshold) {
        return 0;
    }

    return 40;
}

function calculateGrandTotal(subtotal, tax, shipping) {
    return subtotal + tax + shipping;
}

function createPlaceholderSource() {
    return "data:image/svg+xml;charset=UTF-8," + encodeURIComponent(
        '<svg xmlns="http://www.w3.org/2000/svg" width="130" height="130" viewBox="0 0 130 130">' +
            '<rect width="130" height="130" fill="#d8d8d8" />' +
            '<rect x="6" y="6" width="118" height="118" fill="#f3f3f3" stroke="#c9c9c9" />' +
            '<text x="50%" y="48%" text-anchor="middle" font-family="Arial, sans-serif" font-size="14" fill="#666666">Image</text>' +
            '<text x="50%" y="66%" text-anchor="middle" font-family="Arial, sans-serif" font-size="12" fill="#666666">Placeholder</text>' +
        "</svg>"
    );
}        

function outputCartRow(file, title, quantity, price, total) {
    var imageSource = "images/" + file;
    var placeholderSource = createPlaceholderSource();

    document.write("<tr>");
    document.write('<td><img src="' + imageSource + '" alt="' + title + '" onerror="this.onerror=null;this.src=\'' + placeholderSource + '\';"></td>');
    document.write("<td>" + title + "</td>");
    document.write("<td>" + quantity + "</td>");
    document.write("<td>" + outputCurrency(price) + "</td>");
    document.write("<td>" + outputCurrency(total) + "</td>");
    document.write("</tr>");
}

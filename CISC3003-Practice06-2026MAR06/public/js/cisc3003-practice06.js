/* add loop and other code here ... in this simple exercise we are not
   going to concern ourselves with minimizing globals, etc */

var i;
var total;
var subtotal;
var tax;
var shipping;
var grandTotal;
var taxRate;
var shippingThreshold;

subtotal = 0;
taxRate = 0.10;
shippingThreshold = 1000;

for (i = 0; i < titles.length; i++) {
    total = calculateTotal(quantities[i], prices[i]);
    subtotal += total;
    outputCartRow(filenames[i], titles[i], quantities[i], prices[i], total);
}

tax = calculateTax(subtotal, taxRate);
shipping = calculateShipping(subtotal, shippingThreshold);
grandTotal = calculateGrandTotal(subtotal, tax, shipping);

document.write('<tr class="totals"><td colspan="4">Subtotal</td><td>' + outputCurrency(subtotal) + "</td></tr>");
document.write('<tr class="totals"><td colspan="4">Tax</td><td>' + outputCurrency(tax) + "</td></tr>");
document.write('<tr class="totals"><td colspan="4">Shipping</td><td>' + outputCurrency(shipping) + "</td></tr>");
document.write('<tr class="totals focus"><td colspan="4">Grand Total</td><td>' + outputCurrency(grandTotal) + "</td></tr>");


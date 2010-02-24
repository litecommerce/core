/* SVN $Id$ */

function updateProductQuantity(productId, delta)
{
	var input = document.getElementById('product_quantity_' + productId);
	var lower = document.getElementById('product_quantity_' + productId + '_lower');
	var upper = document.getElementById('product_quantity_' + productId + '_upper');

	var productData = (typeof(window.productQuantityData) != 'undefined' && typeof(productQuantityData[productId]) != 'undefined' && typeof(productQuantityData[productId].minAmount) != 'undefined' && typeof(productQuantityData[productId].maxAmount) != 'undefined')
		? productQuantityData[productId]
		: null;

	if (input && lower && upper && productData) {
		var currentAmount = parseInt(input.value);
		if (isNaN(currentAmount)) {
			currentAmount = 1;
		}

		var newAmount = currentAmount;
		delta = parseInt(delta);

		if (!isNaN(delta) && delta != 0) {
			newAmount = Math.max(Math.min(currentAmount + delta, productData.maxAmount), productData.minAmount);
		}

		input.value = newAmount;

		if (newAmount == productData.minAmount) {
			$(lower).addClass('disabled');

		} else {
			$(lower).removeClass('disabled');
		}

        if (newAmount == productData.maxAmount) {
            $(upper).addClass('disabled');

        } else {
            $(upper).removeClass('disabled');
        }
	}

	return true;
}

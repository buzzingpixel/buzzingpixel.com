let tries = 0;

let debouncer = 0;

let timer = 0;

const ajaxUpdateCart = (modelData) => {
    const keys = Object.keys(modelData.cartItems);

    const data = [];

    keys.forEach((key) => {
        data.push({
            slug: key,
            quantity: parseInt(
                modelData.cartItems[key].quantity,
                10,
            ),
        });
    });

    window.axios.post('/ajax/cart/update', data).then((response) => {
        modelData.totalItems = response.data.totalItems;
        modelData.subTotal = response.data.subTotal;
        modelData.tax = response.data.tax;
        modelData.total = response.data.total;
    });
};

const ajaxUpdateCartDebounce = (modelData) => {
    clearTimeout(timer);

    timer = setTimeout(() => {
        ajaxUpdateCart(modelData);
    }, 200);
};

const runDebounce = (modelData) => {
    if (tries > 100) {
        throw new Error('Could not update cart');
    }

    if (!window.axios) {
        debouncer = setTimeout(() => {
            tries += 1;

            runDebounce(modelData);
        }, 50);

        return;
    }

    tries = 0;

    window.addEventListener('userUpdatedCart', () => {
        ajaxUpdateCartDebounce(modelData);
    });
};

const run = (modelData) => {
    clearTimeout(debouncer);

    debouncer = setTimeout(() => {
        runDebounce(modelData);
    }, 300);
};

/**
 * @param {Object} modelData
 * @param {Object} modelData.cartItems
 * @param {number} modelData.totalItems
 * @param {string} modelData.subTotal
 * @param {string} modelData.tax
 * @param {string} modelData.total
 */
export default (modelData) => {
    run(modelData);
};

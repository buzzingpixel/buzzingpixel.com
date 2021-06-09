import LoadStripe from '../Helpers/LoadStripe.js';

let tries = 0;

let debouncer = 0;

let stripe = null;

const createCheckoutSession = () => {
    window.axios.get('/ajax/cart/create-checkout-session').then((response) => {
        // stripe.redirectToCheckout({ sessionId: response.data.sessionId });
        stripe.redirectToCheckout({ sessionId: response.data.sessionId });
    });
};

/**
 * @param {Element} payNow
 */
const runDebounce = (payNow) => {
    if (tries > 100) {
        throw new Error('Could not update cart');
    }

    if (!window.axios) {
        debouncer = setTimeout(() => {
            tries += 1;

            runDebounce(payNow);
        }, 50);

        return;
    }

    tries = 0;

    payNow.addEventListener('click', (e) => {
        e.preventDefault();

        e.currentTarget.textContent = e.currentTarget.dataset.workingText;

        e.currentTarget.style.pointerEvents = 'none';

        e.currentTarget.style.cursor = 'default';

        e.currentTarget.classList.add(e.currentTarget.dataset.workingClass);

        createCheckoutSession();
    });
};

/**
 * @param {Element} payNow
 */
const run = (payNow) => {
    clearTimeout(debouncer);

    debouncer = setTimeout(() => {
        runDebounce(payNow);
    }, 300);
};

/**
 * @param {Element} payNow
 */
export default (payNow) => {
    if (!payNow) {
        return;
    }

    LoadStripe.load().then((s) => {
        stripe = s;

        run(payNow);
    });
};

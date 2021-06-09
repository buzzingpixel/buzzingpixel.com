import Loader from './Loader.js';

class LoadStripe {
    static load () {
        return new Promise((resolve, reject) => {
            Loader.loadJs('https://js.stripe.com/v3/')
                .then(() => {
                    resolve(window.Stripe(window.appInfo.stripePublishableKey));
                })
                .catch(reject);
        });
    }
}

export default LoadStripe;

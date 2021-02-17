import Loader from '../Helpers/Loader.js';

const axiosJs = 'https://cdnjs.cloudflare.com/ajax/libs/axios/0.19.2/axios.min.js';

export default () => {
    Loader.loadJs(axiosJs);
};

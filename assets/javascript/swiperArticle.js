import Swiper from 'swiper';
import {Pagination, Autoplay} from 'swiper/modules';
import 'swiper/scss';
import 'swiper/scss/pagination';

const swiperArticle = new Swiper('.slider-article', {
    modules: [Pagination, Autoplay],
    direction: 'horizontal',
    loop: true,
    grabCursor: true,
    autoplay:{
        delay: 3000,
        disableOnInteraction: true,
    },
    pagination: {
        el: '.swiper-pagination',
        clickable: true,
    },
});
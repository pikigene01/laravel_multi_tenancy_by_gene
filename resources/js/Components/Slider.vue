<template>
    <div class="">
        <div id="carouselExampleCaptions" class="carousel slide" data-ride="carousel" >

            <div class="carousel-inner">
                <div class="carousel-item active">
                    <img src="/images/slider1.jpg" class="d-block w-100 slider_bg" alt="...">
                    <div class="overlay"></div>
                    <div class="carousel-caption  text-start">
                        <h5 class="qrt-white qrt-mb-30" style="font-size: 80px;font-weight: bolder">Welcome To The Home
                            Of Events </h5>

                        <span class="qrt-divider-2"></span>
                        <img src="/logowithhut.png" style="width: 200px;">
                        <p class="caption-title" > The Meet Padora Venue is renowned for its beautiful gardens and
                            collection of fine Shona Sculptures.</p>
                        <Link href="/events" class="btn btn-success p-xl-3">EXPLORE NOW <i
                            class="fas fa-arrow-right arrow-icon"></i>
                        </Link>
                        <Link href="/contact" class="btn btn-outline-light m-lg-3 p-xl-3 text-white">CONTACT US <i
                            class="fas fa-arrow-right arrow-icon"></i></Link>
                    </div>
                </div>
                <div class="carousel-item ">
                    <img src="/images/slider2.jpeg" class="d-block w-100 slider_bg" alt="...">
                    <div class="overlay"></div>
                    <div class="carousel-caption text-start">
                        <h5 class="qrt-white qrt-mb-30">Welcome To The Home
                            Of Events </h5>

                        <span class="qrt-divider-2"></span>
                        <img src="/logowithhut.png" style="width: 200px;">
                        <p class="caption-title"> The Meet Padora Venue is renowned for its beautiful gardens and
                            collection of fine Shona Sculptures.</p>
                        <Link href="/events" class="btn btn-success p-xl-3">EXPLORE NOW <i
                            class="fas fa-arrow-right arrow-icon"></i>
                        </Link>
                        <Link href="/contact" class="btn btn-outline-light m-lg-3 p-xl-3 text-white">CONTACT US <i
                            class="fas fa-arrow-right arrow-icon"></i></Link>
                    </div>
                </div>
                <div class="carousel-item ">
                    <img src="/images/hillslider.jpeg" class="d-block w-100 slider_bg" alt="...">
                    <div class="overlay"></div>
                    <div class="carousel-caption text-start">
                        <h5 class="qrt-white qrt-mb-30">Welcome To The Home
                            Of Events </h5>

                        <span class="qrt-divider-2"></span>
                        <img src="/logowithhut.png" style="width: 200px;">
                        <p class="caption-title"> The Meet Padora Venue is renowned for its beautiful gardens and
                            collection of fine Shona Sculptures.</p>
                        <Link href="/events" class="btn btn-success p-xl-3">EXPLORE NOW <i
                            class="fas fa-arrow-right arrow-icon"></i>
                        </Link>
                        <Link href="/contact" class="btn btn-outline-light m-lg-3 p-xl-3 text-white">CONTACT US <i
                            class="fas fa-arrow-right arrow-icon"></i></Link>
                    </div>
                </div>

            </div>
            <div class="carousel-indicators-yedu ">
                <div class="carousel-btns-align">
                    <button type="button" data-bs-slide-to="prev" class="carousel-control"
                            data-bs-target="#carouselExampleCaptions"
                    ><i class="fas fa-arrow-left"></i> PREV
                    </button>

                    <button type="button" data-bs-slide-to="next" class="carousel-control"
                            data-bs-target="#carouselExampleCaptions"
                            aria-label="Slide 3">NEXT <i class="fas fa-arrow-right"></i></button>
                </div>

            </div>
        </div>

    </div>
</template>

<script>
import {Link} from "@inertiajs/inertia-vue3";

export default {
    components: {
        Link
    }
}

</script>

<style scoped>
.carousel-item {
    height: 90vh !important;
}
.btn{
    margin: 10px 15px !important;
}
@media  screen and (min-height: 1000px) {
.carousel-item {
    height: 1000px !important;
}
.slider_bg{
    height: 1000px;
}
}
h5 {
    color: white;
    font-size: 20px !important;font-weight: bolder !important;
    margin-top: -50px !important;

}
.carousel-caption p{
    font-size: 10px !important;
  margin-top: -35px !important;
}
.slider_bg{
    height: 90vh;
}
.carousel-caption p{
    font-size: 15px !important;
  margin-top: -35px !important;
}
@media screen and (max-width: 400px) {
     .btn {
        margin: 0px 10px;
    }
.carousel-item {
    height: 500px !important;
}
}

p {
    color: white;
    font-size: 15px;

}

h5 {
    color: white;
    font-size: 80px;font-weight: bolder;
}

.carousel-caption {

    top: 50%;
    transform: translateY(-50%);


}
.caption-title{
    font-size: 19px;
}

@media screen and (max-width: 400px) {
       .carousel-item {
    height: 80vh !important;
}
    .btn {
        margin: 0px 10px;
    }
.carousel-caption {
    font-size: 5px !important;
     top: 30%;
    transform: translateY(-50%);

}
.caption-title{
    font-size: 7px;
}
h5 {
    color: white;
    font-size: 20px !important;font-weight: bolder !important;
    margin-top: 20px;

}
.carousel-caption p{
    font-size: 10px !important;
  margin-top: -35px !important;
}
.slider_bg{
    height: 80vh;
}
}
@media screen and (max-width: 412px) {
    .carousel-item {
    height: 80vh !important;
}
.carousel-caption {
    font-size: 5px !important;
     top: 40%;
    transform: translateY(-50%);

}
.caption-title{
    font-size: 7px;
}
h5 {
    color: white;
    font-size: 20px !important;font-weight: bolder !important;
    margin-top: 20px;

}
.carousel-caption p{
    font-size: 10px !important;
  margin-top: -35px !important;
}
.slider_bg{
    height: 80vh;
}

}
@media screen and (max-width: 280px) {
  .carousel-item {
    height: 80vh !important;
}
.carousel-caption {
    font-size: 5px !important;
     top: 30%;
    transform: translateY(-50%);

}
.caption-title{
    font-size: 7px;
}
h5 {
    color: white;
    font-size: 15px !important;font-weight: bolder !important;
    margin-top: 10px !important;

}
.carousel-caption p{
    font-size: 10px !important;
  margin-top: -35px !important;
}
.slider_bg{
    height: 80vh;
}

}
.overlay:after {
    content: "";
    display: block;
    position: absolute;
    top: 0;
    bottom: 0;
    left: 0;
    right: 0;
    background: rgba(0, 0, 0, 0.5);
}

.qrt-divider-2 {
    display: block;
    background: #5ABA47;
    width: 90px;
    margin: 0 0 20px;
    height: 2px;
    border-radius: 1px;
}

.slider-bottom-nav-buttons {
    transform: translateY(0);
    transition: 0.4s ease-in-out;
    transition-delay: 0.8s;
}

.carousel-indicators-yedu {
    display: flex !important;
    justify-content: flex-end !important;
    align-items: flex-end !important;
    width: 100% !important;
    transform: translateY(0);
    transition: 0.4s ease-in-out;
    transition-delay: 0.8s;
    margin-top: -80px;

}

.carousel-btns-align {
    display: flex;
    flex-direction: row;
    justify-content: space-around;
    align-items: center;
    color: green;
    width: 400px;
    height: 80px;
    background: #fcfcfc;
    border-bottom: 3px solid green;

}

.carousel-btns-align button {
    padding: 20px 30px;
    background: transparent;
    outline: none;
    border: none;
    color: green;
}
</style>

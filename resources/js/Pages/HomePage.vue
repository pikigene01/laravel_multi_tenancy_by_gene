<template>
<body class="{{ this.app_values['app_color'] }}">

   <FrontHeaderVue :app_values="this.app_values">
   </FrontHeaderVue>
<header id="home" class="bg-primary">
    <div class="container">
        <div class="row align-items-center justify-content-between">
            <div class="col-sm-5">
                <h1 class="text-white mb-sm-4 wow animate__fadeInLeft" data-wow-delay="0.2s">
                    {{ this.app_values['app_name'] }}
                </h1>
                <h2 class="text-white mb-sm-4 wow animate__fadeInLeft" data-wow-delay="0.4s">
                                        {{ this.app_values['app_title'] }}

                    <br />
                </h2>
                <p class="mb-sm-4 wow animate__fadeInLeft" data-wow-delay="0.6s">
                                      {{ this.app_values['apps_paragraph'] }}

                </p>
            </div>
            <div class="col-sm-5">
                    {{ this.app_values['image'] }}
            </div>
        </div>
    </div>
</header>

<section class="">
    <div class="container">
        <div class="row align-items-center justify-content-end mb-5">
            <div class="col-sm-4">
                <h1 class="mb-sm-4 f-w-600 wow animate__fadeInLeft" data-wow-delay="0.2s">
                   {{ this.app_values['menu_name'] }}
                </h1>
                <h2 class="mb-sm-4 wow animate__fadeInLeft" data-wow-delay="0.4s">
                   {{ this.app_values['menu_subtitle'] }}
                    <br />
                   {{ this.app_values['menu_title'] }}
                </h2>
                <p class="mb-sm-4 wow animate__fadeInLeft" data-wow-delay="0.6s">
                   {{ this.app_values['menu_paragraph'] }}
                </p>
            </div>
                <div class="col-sm-6" v-if="this.app_values['tenant_id'] == null">
                    <img :src="this.app_values['images1']"
                        alt="Datta Able Admin Template" class="img-fluid header-img wow animate__fadeInRight"
                        data-wow-delay="0.2s" />
                </div>

                <div class="col-sm-6" v-else>
                    <img :src="this.app_values['tenant_images1']"
                        alt="Datta Able Admin Template" class="img-fluid header-img wow animate__fadeInRight"
                        data-wow-delay="0.2s" />
                </div>

        </div>
        <div class="row align-items-center justify-content-start">
            <div class="col-sm-6">
                    <img v-if="this.app_values['tenant_id'] == null" :src="this.app_values['images2']"
                        alt="Datta Able Admin Template" class="img-fluid header-img wow animate__fadeInLeft"
                        data-wow-delay="0.2s" />

                    <img v-else :src="this.app_values['images2']"
                        alt="Datta Able Admin Template" class="img-fluid header-img wow animate__fadeInLeft"
                        data-wow-delay="0.2s" />

            </div>
            <div class="col-sm-4">
                <h1 class="mb-sm-4 f-w-600 wow animate__fadeInRight" data-wow-delay="0.2s">
                  {{ this.app_values['submenu_name'] }}
                </h1>
                <h2 class="mb-sm-4 wow animate__fadeInRight" data-wow-delay="0.4s">
                   {{ this.app_values['submenu_subtitle'] }}
                    <br />
                   {{ this.app_values['submenu_title'] }}
                </h2>
                <p class="mb-sm-4 wow animate__fadeInRight" data-wow-delay="0.6s">
                   {{ this.app_values['submenu_paragraph'] }}
                </p>
            </div>
        </div>
    </div>
</section>


<section id="feature" class="feature">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-6 col-md-9 title">
                <h2>
                    <span class="d-block mb-3">
                        {{ this.app_values['feature_name'] }}
                    </span>
                   {{ this.app_values['feature_title'] }}
                </h2>
                <p class="m-0">
                    {{ this.app_values['feature_paragraph'] }}
                </p>
            </div>
        </div>
        <div class="row justify-content-center">
                    <div v-for="(feature, index) in this.features" :key="index">
                    <div class="col-lg-3 col-md-6">
                        <div class="card wow animate__fadeInUp" data-wow-delay="0.2s"
                            style="
                            visibility: visible;
                            animation-delay: 0.2s;
                            animation-name: fadeInUp;">
                            <div class="card-body">
                                <div class="theme-avtar {{ feature->theme_color }}">
                                    <i class="{{ feature->avtar_format }}"></i>
                                </div>
                                <h6 class="text-muted mt-4">
                                    {{ feature.feature_subname }}
                                </h6>
                                <h4 class="my-3 f-w-600">
                                    {{ feature.feature_subtitle }}
                                </h4>
                                <p class="mb-0">
                                    {{ feature.feature_subparagraph }}
                                </p>
                            </div>
                        </div>
                    </div>
                    </div>
        </div>
    </div>
</section>


    <section v-if="this.app_values['tenant_id'] != null" id="feature" class="feature">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xl-6 col-md-9 title">
                    <h2>
                        <span class="d-block mb-3">
                            {{ this.app_values['post_name'] }}
                        </span>
                    </h2>
                    <p class="m-0">
                      {{ this.app_values['post_title'] }}
                    </p>
                </div>
            </div>
            <div class="row justify-content-center">
                <div v-for="(post,index) in this.posts" :key="index">
                    <div class="col-lg-3 col-md-6">
                        <div class="card wow animate__fadeInUp" data-wow-delay="0.2s"
                            style="
                            visibility: visible;
                            animation-delay: 0.2s;
                            animation-name: fadeInUp;">
                            <img class="img-fluid card-img-top card-img-custom"
                                src="{{ 'Storage::url(tenant('id')' . '/' . post.photo) }}" alt="Card image cap">
                            <div class="card-body">
                                <h5 class="card-title">{{ post.title }}</h5>
                                <p class="card-text">
                                    {{ post.short_description.substr(0, 75) +''+'' (post.short_description).length > 75 ? '...' : '' }}
                                </p>
                                <a href="{{ '/post/details', post->slug }}">{{ 'Read More' }}<i
                                        class="ti ti-chevron-right ms-2"></i></a>
                            </div>
                        </div>
                    </div>
                    </div>
            </div>
        </div>
    </section>


    <section id="feature" class="feature" v-if="this.app_values['tenant_id'] != null">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xl-6 col-md-9 title">
                    <h2>
                        <span class="d-block mb-3">
                           {{ this.app_values['post_name'] }}
                        </span>
                    </h2>
                    <p class="m-0">
                        {{ this.app_values['post_title'] }}
                    </p>
                </div>
            </div>
            <div class="row justify-content-center">

                    <div class="col-lg-3 col-md-6" v-for="(post,index) in posts" :key="index">
                        <div class="card wow animate__fadeInUp" data-wow-delay="0.2s"
                            style="
                            visibility: visible;
                            animation-delay: 0.2s;
                            animation-name: fadeInUp;">
                            <img class="img-fluid card-img-top card-img-custom"
                                src="{{ '/storage/' this.app_values['tenant_id'] + '/' + post->photo }}" alt="Card image cap">
                            <div class="card-body">
                                <h5 class="card-title">{{ post.title }}</h5>
                                <p class="card-text">
                        {{ post.short_description.substr(0, 75) +''+'' (post.short_description).length > 75 ? '...' : '' }}

                                </p>
                                <a href="{{`/post/details/ + ${post.slug} `}}">{{ 'Read More' }}<i
                                        class="ti ti-chevron-right ms-2"></i></a>
                            </div>
                        </div>
                    </div>
            </div>
        </div>
    </section>


    <section id="plans" class="price-section" v-if="this.app_values['tenant_id'] == null">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xl-6 col-md-9 title">
                    <h2>
                        <span class="d-block mb-3">
                            {{this.app_values['price_title']}}
                        </span>
                    </h2>
                    <p class="m-0">
                            {{this.app_values['price_paragraph']}}
                    </p>
                </div>
            </div>
            <div class="row justify-content-center">
                <div v-for="(plan,index) in this.plans" class="col-lg-4 col-md-6" :key="index" style="display:flex;">
                        <div v-if="plan.active_status == 1">
                            <div class="card price-card price-1 wow animate__fadeInUp" data-wow-delay="0.2s"
                                style="
                                visibility: visible;
                                animation-delay: 0.2s;
                                animation-name: fadeInUp;">
                                <div class="card-body">
                                    <span class="price-badge bg-primary">{{ plan.name }}</span>
                                    <span class="mb-4 f-w-600 p-price">{{ this.app_values['app_currency'] + '' + plan.price }}<small
                                            class="text-sm">/{{ plan.duration + ' ' + plan.durationtype }}</small></span>
                                    <p class="mb-0">
                                        {{ 'You have Free Unlimited Updates and' }} <br />
                                        {{ 'Premium Support on each package.' }}
                                    </p>
                                    <ul class="list-unstyled my-5">
                                        <li>
                                            <span class="theme-avtar">
                                            </span>
                                        </li>
                                    </ul>
                                    <div class="d-grid text-center">

                                            <div class="pricing-cta" v-if="plan.id == 1">
                                                <Link :href="plan_links[index].link"
                                                    class="subscribe_plan btn btn-primary btn-block mt-2 btn btn-primary btn-block mt-2"
                                                    :data-id="plan.id "
                                                    :data-amount="plan.price">{{ 'Free' }}
                                                    <i class="ti ti-chevron-right ms-2"></i></Link>
                                            </div>
                                            <div class="pricing-cta" v-else-if="plan.id != 1">
                                                <Link :href="plan_links[index].link"
                                                    class="subscribe_plan btn btn-primary btn-block mt-2 btn btn-primary btn-block mt-2"
                                                    :data-id="plan.id"
                                                    :data-amount="plan.price">{{ 'Subscribe' }}
                                                    <i class="ti ti-chevron-right ms-2"></i></Link>
                                            </div>

                                    </div>
                                </div>
                            </div>
                        </div>

                </div>

            </div>
        </div>
    </section>

<section class="faq">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-6 col-md-9 title">
                <h2>
                    {{this.app_values['faq_title']}}
                </h2>
                <p class="m-0">
                    {{this.app_values['faq_paragraph']}}

                </p>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-sm-12 col-md-10 col-xxl-8">
                <div class="accordion accordion-flush" id="accordionExample">

                        <div class="accordion-item card" v-for="(faq,index) in this.faqs" :key="index">
                            <h2 class="accordion-header" id="headingTwo">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#collapseTwo{{ faq->id }}" aria-expanded="false"
                                    aria-controls="collapseTwo{{ faq.id }}">
                                    <span class="d-flex align-items-center">
                                        <i class="ti ti-info-circle text-primary"></i>
                                        {{ faq.quetion }}
                                    </span>
                                </button>
                            </h2>
                            <div id="collapseTwo{{ faq.id }}" class="accordion-collapse collapse"
                                aria-labelledby="headingTwo" data-bs-parent="#accordionExample">
                                <div class="accordion-body">
                                    {{ faq.answer }}
                                </div>
                            </div>
                        </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="side-feature">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-sm-3">
                <h1 class="mb-sm-4 f-w-600 wow animate__fadeInLeft" data-wow-delay="0.2s">
                    {{ this.app_values['sidefeature_name'] }}
                </h1>
                <h2 class="mb-sm-4 wow animate__fadeInLeft" data-wow-delay="0.4s">

                    {{ this.app_values['sidefeature_title'] }}

                    <br />
                    {{ this.app_values['sidefeature_subtitle'] }}

                </h2>
                <p class="mb-sm-4 wow animate__fadeInLeft" data-wow-delay="0.6s">
                    {{ this.app_values['sidefeature_paragraph'] }}

                </p>
            </div>
            <div class="col-sm-9">
                <div class="row gy-4 feature-img-row">
                    <div v-if="this.app_values['tenant_id'] == null">
                        <div class="col-3">
                            <img :src="this.app_values['images1']"
                                class="img-fluid header-img wow animate__fadeInRight card-img-top card-img-custom m-2"
                                data-wow-delay="0.2s" alt="Admin" />
                        </div>
                        <div class="col-3">
                            <img :src="this.app_values['images2']"
                                class="img-fluid header-img wow animate__fadeInRight card-img-top card-img-custom m-2"
                                data-wow-delay="0.4s" alt="Admin" />
                        </div>
                        <div class="col-3">
                            <img :src="this.app_values['images3']"
                                class="img-fluid header-img wow animate__fadeInRight card-img-top card-img-custom m-2"
                                data-wow-delay="0.6s" alt="Admin" />
                        </div>
                        <div class="col-3">
                            <img :src="this.app_values['images4']"
                                class="img-fluid header-img wow animate__fadeInRight card-img-top card-img-custom m-2"
                                data-wow-delay="0.8s" alt="Admin" />
                        </div>
                        <div class="col-3">
                            <img :src="this.app_values['images5']"
                                class="img-fluid header-img wow animate__fadeInRight card-img-top card-img-custom m-2"
                                data-wow-delay="0.3s" alt="Admin" />
                        </div>
                        <div class="col-3">
                            <img :src="this.app_values['images6']"
                                class="img-fluid header-img wow animate__fadeInRight card-img-top card-img-custom m-2"
                                data-wow-delay="0.5s" alt="Admin" />
                        </div>
                        <div class="col-3">
                            <img :src="this.app_values['images7']"
                                class="img-fluid header-img wow animate__fadeInRight card-img-top card-img-custom m-2"
                                data-wow-delay="0.7s" alt="Admin" />
                        </div>
                        <div class="col-3">
                            <img :src="this.app_values['images8']"
                                class="img-fluid header-img wow animate__fadeInRight card-img-top card-img-custom m-2"
                                data-wow-delay="0.9s" alt="Admin" />
                        </div>
                        </div>
                       <div v-else>
                        <div class="col-3">
                            <img :src="this.app_values['images1']"
                                class="img-fluid header-img wow animate__fadeInRight card-img-top card-img-custom m-2"
                                data-wow-delay="0.2s" alt="Admin" />
                        </div>
                        <div class="col-3">
                            <img :src="this.app_values['images2']"
                                class="img-fluid header-img wow animate__fadeInRight card-img-top card-img-custom m-2"
                                data-wow-delay="0.4s" alt="Admin" />
                        </div>
                        <div class="col-3">
                            <img :src="this.app_values['images3']"
                                class="img-fluid header-img wow animate__fadeInRight card-img-top card-img-custom m-2"
                                data-wow-delay="0.6s" alt="Admin" />
                        </div>
                        <div class="col-3">
                            <img :src="this.app_values['images4']"
                                class="img-fluid header-img wow animate__fadeInRight card-img-top card-img-custom m-2"
                                data-wow-delay="0.8s" alt="Admin" />
                        </div>
                        <div class="col-3">
                            <img :src="this.app_values['images5']"
                                class="img-fluid header-img wow animate__fadeInRight card-img-top card-img-custom m-2"
                                data-wow-delay="0.3s" alt="Admin" />
                        </div>
                        <div class="col-3">
                            <img :src="this.app_values['images6']"
                                class="img-fluid header-img wow animate__fadeInRight card-img-top card-img-custom m-2"
                                data-wow-delay="0.5s" alt="Admin" />
                        </div>
                        <div class="col-3">
                            <img :src="this.app_values['images7']"
                                class="img-fluid header-img wow animate__fadeInRight card-img-top card-img-custom m-2"
                                data-wow-delay="0.7s" alt="Admin" />
                        </div>
                        <div class="col-3">
                            <img :src="this.app_values['images8']"
                                class="img-fluid header-img wow animate__fadeInRight card-img-top card-img-custom m-2"
                                data-wow-delay="0.9s" alt="Admin" />
                        </div>
                       </div>
                </div>
            </div>
        </div>

    </div></section>

</body>

</template>
 <script>
import { useForm,Link } from "@inertiajs/inertia-vue3";
import { Inertia } from "@inertiajs/inertia";
import FrontHeaderVue from './Layouts/FrontHeader.vue';
export default {
  components: { FrontHeaderVue,Link },
  props: ["Utility", "blogs", "app_values", "features","plans","plan_links"],

  data() {
    return {
      isEdit: false,
      isEditRange: false,
      categoryForm: useForm({
       name: null,
        id: null,
      }),
      deleteBlogForm: useForm({
        id: null,
      }),
      blogsForm: useForm({
        title: null,
        description: null,
        category_id: null,
        image: null,
        id: null,
      }),
    };
  },
 mounted(){


const p_tags = document.querySelectorAll('.ck-content p');

p_tags.forEach((p)=>{

alert(p.innerHTML);

});
let editorData = '';
window.ClassicEditor
    .create( document.querySelector( '#editor' ) )
    .then( editor => {
        editor.model.document.on( 'change:data', () => {
            editorData = editor.getData();
            this.blogsForm.description = editorData;
        } );
    } )
    .catch( error => {
        console.error( error );
    } );
  },
  methods: {
getCKdata(e){
const p_tags = document.querySelectorAll('.ck-content p');

p_tags.forEach((p)=>{
console.log(p.innerHTML);
});
},
    liveSearch(e){
 // Locate the card elements
    const cards = document.querySelectorAll('.search_all')
    // Locate the search input
    let search_query = e.target.value;
    // Loop through the cards
    for (var i = 0; i < cards.length; i++) {
    // If the text is within the card...
    if(cards[i].innerText.toLowerCase()
    // ...and the text matches the search query...
    .includes(search_query.toLowerCase())) {
    // ...remove the `.is-hidden` class.
    cards[i].classList.remove("is-hidden");
    } else {
    // Otherwise, add the class.
    cards[i].classList.add("is-hidden");
    }
    }
    },
    deleteCategory(id) {
      this.categoryForm.post(`/admin/category/delete/${id}`);
    },
    deleteBlogs(id) {
      this.deleteBlogForm.post(`/admin/blogs/delete/${id}`);
    },

    edit(range) {
      this.isEditRange = true;
      this.categoryForm.name = this.categoryForm.name;

    },
    submitEditCategory() {
      this.categoryForm.post(`/admin/category/update/${this.categoryForm.id}`, {
        preserveScroll: true,
        onSuccess: () => {
          this.isEditRange = false;
          this.categoryForm.reset();
          document.getElementById("closeRangeModel").click();
        },
      });
    },
    submitSaveCategories() {
      this.categoryForm.post("/admin/blogs/category/add", {
        preserveScroll: true,
        onSuccess: () => {
          this.categoryForm.reset();
          document.getElementById("closeRangeModel").click();
        },
      });
    },
    submitEdit() {
      this.blogsForm.post(`/admin/blogs/update/${this.blogsForm.id}`, {
        preserveScroll: true,
        onSuccess: () => {
          this.isEditRange = false;
          this.blogsForm.reset();
          document.getElementById("closeEventModel").click();
        },
      });
    },
    submitSave() {
      this.blogsForm.post("/admin/blogs", {
        preserveScroll: true,
        onSuccess: () => {
          this.blogsForm.reset();
          document.getElementById("closeEventModel").click();
        },
      });
    },

  },
};
</script>

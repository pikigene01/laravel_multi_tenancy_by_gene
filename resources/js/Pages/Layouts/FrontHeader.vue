<template>
    <!-- <h3>{{this.app_values['app_color']}}</h3> -->

    <head>
        <title>{{ this.app_values["app_name"] }}</title>

        <!-- Favicon icon -->
        <!-- <meta name="csrf-token" content="{{ csrf_token() }}"> -->
        <meta name="csrf-token" :content="this.app_values['csrf_token']" />

        <link rel="icon" :href="this.app_values['apps_paragraph']" />

        <link rel="stylesheet" href="assets/css/plugins/animate.min.css" />
        <!-- font css -->
        <link rel="stylesheet" href="assets/fonts/tabler-icons.min.css" />
        <link rel="stylesheet" href="assets/fonts/feather.css" />
        <link rel="stylesheet" href="assets/fonts/fontawesome.css" />
        <link rel="stylesheet" href="assets/fonts/material.css" />
        <link rel="stylesheet" href="assets/css/plugins/notifier.css" />

        {{
            this.app_values["link_data"]
        }}

        <link rel="stylesheet" href="assets/css/customizer.css" />
        <link rel="stylesheet" href="assets/css/custom.css" />

        <link rel="stylesheet" href="assets/css/landing.css" />
    </head>
    <!-- [ Nav ] start -->
    <nav class="navbar navbar-expand-md navbar-dark default top-nav-collapse">
        <div class="container">
            <Link class="navbar-brand bg-transparent" :href="'/'">
                <img
                   style="width:103px;"
                    :src="this.app_values['app_icon']"
                    class="app-logo img_setting"
                />
            </Link>
            <button
                class="navbar-toggler"
                type="button"
                data-bs-toggle="collapse"
                data-bs-target="#navbarTogglerDemo01"
                aria-controls="navbarTogglerDemo01"
                aria-expanded="false"
                aria-label="Toggle navigation"
            >
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarTogglerDemo01">
                <ul
                    id="app_links"
                    class="navbar-nav align-items-center ms-auto mb-2 mb-lg-0"
                >
                    <li class="nav-item">
                        <Link class="nav-link active" :href="'/'">{{ "Home" }}</Link>
                    </li>

                    <li class="nav-item" v-if="this.app_values['AuthUser']">
                        <a class="nav-link" href="/home">Dashboard</a>
                    </li>
                    <li class="nav-item" v-else>
                        <a class="nav-link" href="/home">Login</a>
                    </li>

                    <li class="nav-item" v-if="this.app_values['tenant_id'] !== null">
                        <Link class="nav-link" href="/#plans">Plans</Link>
                    </li>
                    <li class="nav-item" v-else>
                        <Link class="nav-link" :href="this.app_values['blogs_link']">Blogs</Link>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- [ Nav ] start -->
    <!-- [ Header ] start -->
</template>
<script>
import { useForm,Link } from "@inertiajs/inertia-vue3";
import { Inertia } from "@inertiajs/inertia";
export default {
      components: { Link },
    props: ["Utility", "blogs", "app_values"],

    data() {
        return {
            isEdit: false,
            link_data: "",
            link_auth: "",
            link_tenant: "",
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
    mounted() {
        if (this.app_values["dark_mode"] == "on") {
            this.link_data += `
        <link rel="stylesheet" href="assets/css/style-dark.css">
    `;
        } else {
            this.link_data += `
        <link rel="stylesheet" href="assets/css/style.css" id="main-style-link">
    `;
        }
        if (this.app_values["AuthUser"]) {
            this.link_auth = `

    `;
        } else {
            this.link_auth = `

    `;
        }
        if (this.app_values["tenant_id"]) {
            this.link_tenant = `

    `;
        } else {
            this.link_tenant = `

    `;
        }
        let links = document.createElement("li");
        links.innerHTML = `
${this.link_auth}
${this.link_tenant}
`;
        document.querySelector("#app_links").appendChild(links);
        const p_tags = document.querySelectorAll(".ck-content p");

        p_tags.forEach((p) => {
            alert(p.innerHTML);
        });
        let editorData = "";
        window.ClassicEditor.create(document.querySelector("#editor"))
            .then((editor) => {
                editor.model.document.on("change:data", () => {
                    editorData = editor.getData();
                    this.blogsForm.description = editorData;
                });
            })
            .catch((error) => {
                console.error(error);
            });
    },
    methods: {
        getCKdata(e) {
            const p_tags = document.querySelectorAll(".ck-content p");

            p_tags.forEach((p) => {
                console.log(p.innerHTML);
            });
        },
        liveSearch(e) {
            // Locate the card elements
            const cards = document.querySelectorAll(".search_all");
            // Locate the search input
            let search_query = e.target.value;
            // Loop through the cards
            for (var i = 0; i < cards.length; i++) {
                // If the text is within the card...
                if (
                    cards[i].innerText
                        .toLowerCase()
                        // ...and the text matches the search query...
                        .includes(search_query.toLowerCase())
                ) {
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
            this.categoryForm.post(
                `/admin/category/update/${this.categoryForm.id}`,
                {
                    preserveScroll: true,
                    onSuccess: () => {
                        this.isEditRange = false;
                        this.categoryForm.reset();
                        document.getElementById("closeRangeModel").click();
                    },
                }
            );
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

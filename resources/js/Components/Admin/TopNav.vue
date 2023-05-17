<template>
  <header class="header">
    <div class="header-inner">
      <nav
        class="
          navbar navbar-expand-lg
          bg-barren
          barren-head
          navbar
          fixed-top
          justify-content-sm-start
          pt-0
          pb-0
          ps-lg-0
          pe-2
        "
      >
        <div class="container-fluid ps-0">
          <button type="button" id="toggleMenu" class="toggle_menu">
            <i class="fa-solid fa-bars-staggered"></i>
          </button>
          <button id="collapse_menu" class="collapse_menu me-4">
            <i class="fa-solid fa-bars collapse_menu--icon"></i>
            <span class="collapse_menu--label"></span>
          </button>
          <button
            class="navbar-toggler order-3 ms-2 pe-0"
            type="button"
            data-bs-toggle="offcanvas"
            data-bs-target="#offcanvasNavbar"
            aria-controls="offcanvasNavbar"
          >
            <span class="navbar-toggler-icon">
              <i class="fa-solid fa-bars"></i>
            </span>
          </button>
          <Link
            class="navbar-brand order-1 order-lg-0 ml-lg-0 ml-2 me-auto"
            href="/"
          >
            <div class="res-main-logo">
              <img src="/logoTransparent.png" alt="" />
            </div>
            <div class="main-logo" id="logo">
              <img src="/logoTransparent.png" alt="" />
              <img class="logo-inverse" src="/logoTransparent.png" alt="" />
            </div>
          </LInk>
          <div
            class="offcanvas offcanvas-start"
            tabindex="-1"
            id="offcanvasNavbar"
            aria-labelledby="offcanvasNavbarLabel"
          >
            <div class="offcanvas-header">
              <div class="offcanvas-logo" id="offcanvasNavbarLabel">
                <img src="/logoTransparent.png" alt="" />
              </div>
              <button
                type="button"
                class="close-btn"
                data-bs-dismiss="offcanvas"
                aria-label="Close"
              >
                <i class="fa-solid fa-xmark"></i>
              </button>
            </div>
            <div class="offcanvas-body">
              <div class="offcanvas-top-area">
                <div class="create-bg">
                  <Link href="/events" class="offcanvas-create-btn">
                    <i class="fa-solid fa-calendar-days"></i>
                    <span>Venues</span>
                  </Link>
                </div>
              </div>
              <ul class="navbar-nav justify-content-end flex-grow-1 pe_5">

              </ul>
            </div>
            <!-- <div class="offcanvas-footer">
              <div class="offcanvas-social">
                <h5>Follow Us</h5>
                <ul class="social-links">
                  <li>
                    <a href="#" class="social-link"
                      ><i class="fab fa-facebook-square"></i
                    ></a>
                  </li>
                  <li>
                    <a href="#" class="social-link"
                      ><i class="fab fa-instagram"></i
                    ></a>
                  </li>
                  <li>
                    <a href="#" class="social-link"
                      ><i class="fab fa-twitter"></i
                    ></a>
                  </li>
                  <li>
                    <a href="#" class="social-link"
                      ><i class="fab fa-linkedin-in"></i
                    ></a>
                  </li>
                  <li>
                    <a href="#" class="social-link"
                      ><i class="fab fa-youtube"></i
                    ></a>
                  </li>
                </ul>
              </div>
            </div> -->
          </div>
          <div class="right-header order-2">
            <ul class="align-self-stretch">
              <!-- <li>
                <a href="create.html" class="create-btn btn-hover">
                  <i class="fa-solid fa-calendar-days"></i>
                  <span>Create Event</span>
                </a>
              </li> -->
              <li class="dropdown account-dropdown">
                <a
                  href="#"
                  class="account-link"
                  role="button"
                  id="accountClick"
                  data-bs-auto-close="outside"
                  data-bs-toggle="dropdown"
                  aria-expanded="false"
                >
                  <img src="/images/user.png" alt />
                  <i class="fas fa-caret-down arrow-icon"></i>
                </a>
                <ul
                  class="dropdown-menu dropdown-menu-account dropdown-menu-end"
                  aria-labelledby="accountClick"
                >
                  <li>
                    <div class="dropdown-account-header">
                      <div class="account-holder-avatar">
                        <img src="/images/user.png" alt />
                      </div>
                      <h5 v-if="$page.props.user">
                        {{ $page.props.user.name }}
                      </h5>
                      <p v-if="$page.props.user">
                        <a href="/" class="__cf_email__">
                          {{ $page.props.user.email }}
                        </a>
                      </p>
                    </div>
                  </li>
                  <li class="profile-link" v-if="$page.props.user">
                    <Link href="/user/dashboard" class="link-item"
                      >My Account</Link
                    >
                    <a href="" @click="logout()" class="link-item">Sign Out</a>
                  </li>
                  <li class="profile-link" v-else>
                    <Link href="/login" class="link-item">Login</Link>
                  </li>
                </ul>
              </li>

            </ul>
          </div>
        </div>
      </nav>
      <div class="overlay"></div>
    </div>
  </header>
</template>

<script>
import { Inertia } from "@inertiajs/inertia";
import { Head, Link } from "@inertiajs/inertia-vue3";

export default {
  methods: {
    logout() {
      Inertia.post(route("logout"));
    },
  },

  components: {
    Link,
  },
};
</script>

<style>
</style>

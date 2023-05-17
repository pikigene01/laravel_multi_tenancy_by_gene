<template>
  <nav class="vertical_nav">
    <div class="left_section menu_left" id="js-menu">
      <div class="left_section">
        <ul>
          <li class="menu--item">
            <Link
              href="/admin"
              class="menu--link active"
              title="Dashboard"
              data-bs-toggle="tooltip"
              data-bs-placement="right"
            >
              <i class="fa-solid fa-gauge menu--icon"></i>
              <span class="menu--label">Dashboard</span>
            </Link>
          </li>
          <li class="menu--item">
            <Link
              href="/admin/event-types"
              class="menu--link"
              title="Venue"
              data-bs-toggle="tooltip"
              data-bs-placement="right"
            >
              <i class="fa-solid fa-calendar-days menu--icon"></i>
              <span class="menu--label">Venue Types</span>
            </Link>
          </li>
          <li class="menu--item">
            <Link
              href="/admin/events"
              class="menu--link"
              title="Events"
              data-bs-toggle="tooltip"
              data-bs-placement="right"
            >
              <i class="fa-solid fa-calendar-days menu--icon"></i>
              <span class="menu--label">Venues</span>
            </Link>

            <Link
              href="/admin/blogs"
              class="menu--link"
              title="Events"
              data-bs-toggle="tooltip"
              data-bs-placement="right"
            >
              <i class="fa-solid fa-book menu--icon"></i>
              <span class="menu--label">Blogs</span>
            </Link>
          </li>

          <li class="menu--item">
            <Link
              href="/admin/events/bookings"
              class="menu--link"
              title="Promotion"
              data-bs-toggle="tooltip"
              data-bs-placement="right"
            >
              <i class="fa-solid fa-rectangle-ad menu--icon"></i>
              <span class="menu--label">User Bookings</span>
            </Link>
          </li>
          <li class="menu--item">
            <Link
              href="/admin/slider-create"
              class="menu--link"
              title="Promotion"
              data-bs-toggle="tooltip"
              data-bs-placement="right"
            >
              <i class="fa-solid fa-rectangle-ad menu--icon"></i>
              <span class="menu--label">Sliders</span>
            </Link>
          </li>
          <li class="menu--item">
            <Link
              href="/admin/footer-create"
              class="menu--link"
              title="Promotion"
              data-bs-toggle="tooltip"
              data-bs-placement="right"
            >
              <i class="fa-solid fa-rectangle-ad menu--icon"></i>
              <span class="menu--label">Footer Links</span>
            </Link>
          </li>
          <li class="menu--item">
            <Link
              href="/admin/clients"
              class="menu--link"
              title="Contact List"
              data-bs-toggle="tooltip"
              data-bs-placement="right"
            >
              <i class="fa-regular fa-address-card menu--icon"></i>
              <span class="menu--label">Clients</span>
            </Link>
          </li>
          <!-- <li class="menu--item">
            <Link
              href="/admin/transactions"
              class="menu--link"
              title="Payouts"
              data-bs-toggle="tooltip"
              data-bs-placement="right"
            >
              <i class="fa-solid fa-credit-card menu--icon"></i>
              <span class="menu--label">Transactions</span>
            </Link>
          </li> -->


          <li class="menu--item">
            <Link
              href="/admin/users"
              class="menu--link team-lock"
              title="My Team"
              data-bs-toggle="tooltip"
              data-bs-placement="right"
            >
              <i class="fa-solid fa-user-group menu--icon"></i>
              <span class="menu--label">My Team</span>
            </Link>
          </li>
        </ul>
      </div>
    </div>
  </nav>
</template>

<script>
import { Head, Link } from "@inertiajs/inertia-vue3";
export default {
  components: {
    Head,
    Link,
  },
};
</script>

<style>
</style>

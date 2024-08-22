import { createRouter, createWebHistory } from "vue-router";
import Login from "../components/Login.vue";
import Review from "../components/Review.vue";
import Booking from "../components/Booking.vue";
import UserManagement from "../components/UserManagement.vue";
import CoachLessons from "../pages/CoachLessons.vue";
import Home from "../pages/Home.vue";
import Register from "../components/Register.vue";
import Coaches from "../pages/Coaches.vue";
import StudentLesson from "../pages/StudentLessons.vue";
import CoachDashboard from "../pages/CoachDashboard.vue";
import PaymentSuccess from "../pages/PaymentSuccess.vue";

const routes = [
  { path: "/login", component: Login },
  { path: "/review", component: Review },
  {
    path: "/booking",
    component: Booking,
    meta: { requiresAuth: true, role: "student" },
  },
  {
    path: "/student/lessons",
    component: StudentLesson,
    meta: { requiresAuth: true, role: "student" },
  },
  {
    path: "/user-management",
    component: UserManagement,
    meta: { requiresAuth: true, role: "admin" },
  },
  {
    path: "/coach/dashboard",
    component: CoachDashboard,
    meta: { requiresAuth: true, role: "coach" },
  },
  {
    path: "/lesson-management",
    component: CoachLessons,
    meta: { requiresAuth: true, role: "coach" },
  },
  {
    path: "/payment-success/:id",
    component: PaymentSuccess,
    meta: { requiresAuth: true, role: "student" },
  },
  {
    path: "/view/coaches",
    component: Coaches,
    meta: { requiresAuth: true, role: "student" },
  },
  { path: "/", component: Home },
  { path: "/register", component: Register },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

router.beforeEach((to, from, next) => {
  const user = JSON.parse(localStorage.getItem("user"));
  const isLoggedIn = !!user;
  const userRole = user ? user.role : null;

  if (to.matched.some((record) => record.meta.requiresAuth)) {
    if (!isLoggedIn) {
      return next("/login");
    }

    if (to.meta.role && to.meta.role !== userRole) {
      if (userRole === "student") {
        return next("/booking");
      } else if (userRole === "coach") {
        return next("/lesson-management");
      } else if (userRole === "admin") {
        return next("/user-management");
      }
    }
  }

  next();
});

export default router;

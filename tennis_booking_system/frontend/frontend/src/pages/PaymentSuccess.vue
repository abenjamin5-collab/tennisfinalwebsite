<template>
  <div class="success-container">
    <h1>Payment Successful!</h1>
    <p>
      Your payment for the lesson with {{ lesson.coach?.first_name }}
      {{ lesson.coach?.last_name }} has been completed successfully.
    </p>
    <router-link to="/" class="btn btn-primary">Go to Dashboard</router-link>
  </div>
</template>

<script>
import axios from "axios";
import { base_url, front_end_base_url } from "../../config/config";
export default {
  data() {
    return {
      lesson: {},
    };
  },
  created() {
    this.fetchLessonDetails();
  },
  methods: {
    fetchLessonDetails() {
      const lessonId = this.$route.params.id;
      axios
        .post(
          `${base_url}/api/payment-success`,
          {
            lesson_id: lessonId,
            session_id: this.$route.query.session_id,
          },
          {
            headers: {
              Authorization: `Bearer ${localStorage.getItem("access_token")}`,
            },
          }
        )
        .then((response) => {
          this.lesson = response.data;
        })
        .catch((error) => {
          console.error("Error fetching lesson details:", error);
        });
    },
  },
};
</script>

<style scoped>
.success-container {
  text-align: center;
  margin-top: 50px;
}

h1 {
  color: #28a745;
  font-size: 2rem;
}

p {
  font-size: 1.2rem;
  color: #333;
}

.btn-primary {
  margin-top: 20px;
  background-color: #007bff;
  border: none;
  padding: 10px 20px;
  border-radius: 8px;
  color: white;
  cursor: pointer;
  transition: background-color 0.3s ease;
}

.btn-primary:hover {
  background-color: #0056b3;
}
</style>

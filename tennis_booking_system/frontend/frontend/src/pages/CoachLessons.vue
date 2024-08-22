<template>
  <div>
    <navbar></navbar>
    <h2>Upcoming Lessons</h2>
    <div class="container">
      <div v-if="bookedLessons.length" class="lessons-list">
        <div
          v-for="lesson in bookedLessons"
          :key="lesson.id"
          class="lesson-card"
        >
          <div class="card-header">
            <div class="student-info">
              <h3>
                {{ lesson.student.first_name }} {{ lesson.student.last_name }}
              </h3>
              <p>Student</p>
            </div>
            <div class="status-badge" :class="statusClass(lesson.status)">
              {{ lesson.status.toUpperCase() }}
            </div>
          </div>
          <div class="card-body">
            <div class="lesson-details">
              <p>
                <i class="far fa-calendar-alt"></i> {{ lesson.booking_date }}
              </p>
              <p><i class="far fa-clock"></i> {{ lesson.booking_time }}</p>
            </div>
            <button
              v-if="lesson.status === 'approved'"
              class="btn btn-primary"
              @click="makePayment(lesson)"
            >
              Pay Now
            </button>
          </div>
        </div>
      </div>
      <div v-else class="no-lessons">
        <p>No upcoming lessons found.</p>
      </div>
    </div>
  </div>
</template>

<script>
import Navbar from "../components/Navbar.vue";
import { base_url } from "../../config/config";
import axios from "axios";
export default {
  components: { Navbar },
  data() {
    return {
      bookedLessons: [],
    };
  },
  created() {
    this.fetchBookedLessons();
  },
  methods: {
    fetchBookedLessons() {
      axios
        .get(`${base_url}/api/coach/booked-lessons`, {
          headers: {
            Authorization: `Bearer ${localStorage.getItem("access_token")}`,
          },
        })
        .then((response) => {
          this.bookedLessons = response.data.data;
        })
        .catch((error) => {
          console.error("Error fetching booked lessons:", error);
        });
    },
    makePayment(lesson) {
      // Implement the payment logic here
      alert(
        `Initiating payment for lesson with student ${lesson.student.name}`
      );
    },
    statusClass(status) {
      return (
        {
          approved: "status-approved",
          scheduled: "status-scheduled",
        }[status] || "status-default"
      );
    },
  },
};
</script>

<style scoped>
.container {
  max-width: 900px;
  margin: 40px auto;
  padding: 0 20px;
}

h2 {
  text-align: center;
  color: #333;
  margin-bottom: 30px;
  font-size: 2rem;
  font-weight: bold;
}

.lessons-list {
  display: flex;
  flex-direction: column;
  gap: 20px;
  width: 50%;
}

.lesson-card {
  background: #fff;
  border-radius: 12px;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
  padding: 40px;
  transition: transform 0.3s ease;
}

.lesson-card:hover {
  transform: translateY(-8px);
}

.card-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 15px;
}

.student-info h3 {
  margin: 0;
  font-size: 1.5rem;
  color: #007bff;
}

.student-info p {
  margin: 0;
  font-size: 0.9rem;
  color: #555;
}

.status-badge {
  padding: 8px 12px;
  border-radius: 8px;
  font-size: 0.85rem;
  font-weight: bold;
  text-transform: uppercase;
}

.status-approved {
  background-color: #28a745;
  color: white;
}

.status-scheduled {
  background-color: #ffc107;
  color: white;
}

.card-body {
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.lesson-details p {
  margin: 0;
  font-size: 1rem;
  color: #666;
  display: flex;
  align-items: center;
}

.lesson-details p i {
  margin-right: 8px;
  color: #007bff;
}

.btn-primary {
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

.no-lessons {
  text-align: center;
  color: #888;
  font-size: 1.2rem;
  margin-top: 20px;
}
</style>

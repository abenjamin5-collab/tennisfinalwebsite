<template>
  <div>
    <navbar></navbar>
    <h2>Past Students</h2>
    <div class="container">
      <br />
      <div v-if="students.length" class="students-list">
        <div v-for="student in students" :key="student.id" class="student-card">
          <div class="card-header">
            <h3>{{ student.first_name }} {{ student.last_name }}</h3>
          </div>
          <div class="card-body">
            <p>Email: {{ student.email }}</p>
          </div>
        </div>
      </div>
      <div v-else class="no-students">
        <p>No past students found.</p>
      </div>
    </div>
  </div>
</template>

<script>
import Navbar from "../components/Navbar.vue";
import axios from "axios";
import { base_url } from "../../config/config";

export default {
  components: { Navbar },
  data() {
    return {
      students: [],
    };
  },
  created() {
    this.fetchPastStudents();
  },
  methods: {
    fetchPastStudents() {
      axios
        .get(`${base_url}/api/coach-past-students`, {
          headers: {
            Authorization: `Bearer ${localStorage.getItem("access_token")}`,
          },
        })
        .then((response) => {
          this.students = response.data.data;
        })
        .catch((error) => {
          console.error("Error fetching past students:", error);
        });
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

.students-list {
  display: flex;
  flex-direction: row;
  gap: 20px;
}

.student-card {
  background: #f8f9fa;
  border-radius: 12px;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
  padding: 20px;
  transition: transform 0.3s ease;
}

.student-card:hover {
  transform: translateY(-8px);
}

.card-header {
  font-size: 1.5rem;
  color: #007bff;
  margin-bottom: 10px;
}

.card-body {
  font-size: 1rem;
  color: #666;
}

.no-students {
  text-align: center;
  color: #888;
  font-size: 1.2rem;
  margin-top: 20px;
}
</style>

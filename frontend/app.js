
const API_BASE = "../backend/api/";


let currentUser = null;


async function registerUser(username, password) {
  const res = await fetch(API_BASE + "users.php", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({ action: "register", username, password })
  });
  return res.json();
}

async function loginUser(username, password) {
  const res = await fetch(API_BASE + "users.php", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({ action: "login", username, password })
  });
  const data = await res.json();
  if (data.success) currentUser = data.user;
  return data;
}


async function getHabits() {
  const res = await fetch(API_BASE + "habits.php?user_id=" + currentUser.id);
  return res.json();
}

async function addHabit(name, description) {
  const res = await fetch(API_BASE + "habits.php", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({ user_id: currentUser.id, name, description })
  });
  return res.json();
}


async function completeHabit(habit_id) {
  const res = await fetch(API_BASE + "completions.php", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({ user_id: currentUser.id, habit_id })
  });
  return res.json();
}

async function getCompletions() {
  const res = await fetch(API_BASE + "completions.php?user_id=" + currentUser.id);
  return res.json();
}


async function getRankings() {
  const res = await fetch(API_BASE + "rankings.php");
  return res.json();
}



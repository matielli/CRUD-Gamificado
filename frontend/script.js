const API_URL = "http://localhost/crud_gamificado/api"; 


document.getElementById("user-form").addEventListener("submit", async (e) => {
  e.preventDefault();
  const name = document.getElementById("name").value;
  const email = document.getElementById("email").value;

  await fetch(`${API_URL}/users/create.php`, {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({ name, email })
  });

  alert("Usuário criado!");
  loadRanking();
  loadBadges();
});


document.getElementById("habit-form").addEventListener("submit", async (e) => {
  e.preventDefault();
  const title = document.getElementById("habit-title").value;
  const description = document.getElementById("habit-desc").value;
  const user_id = document.getElementById("habit-user").value;

  await fetch(`${API_URL}/habits/create.php`, {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({ title, description, user_id })
  });

  alert("Hábito adicionado!");
  loadHabits(user_id);
  loadRanking();
  loadBadges();
});


async function loadHabits(user_id) {
  const res = await fetch(`${API_URL}/habits/read.php?user_id=${user_id}`);
  const data = await res.json();
  const list = document.getElementById("habit-list");
  list.innerHTML = "";
  data.forEach(habit => {
    const li = document.createElement("li");
    li.textContent = `${habit.title} - ${habit.description}`;
    list.appendChild(li);
  });
}


async function loadRanking() {
  const res = await fetch(`${API_URL}/ranking.php`);
  const data = await res.json();
  const list = document.getElementById("ranking-list");
  list.innerHTML = "";
  data.forEach((user, i) => {
    const li = document.createElement("li");
    li.textContent = `#${i+1} ${user.name} - ${user.points} pts`;
    list.appendChild(li);
  });
}


async function loadBadges() {
  const res = await fetch(`${API_URL}/badges/read.php`);
  const data = await res.json();
  const list = document.getElementById("badge-list");
  list.innerHTML = "";
  data.forEach(badge => {
    const li = document.createElement("li");
    li.textContent = `${badge.name} - ${badge.description} (🔥 ${badge.points_required} pts)`;
    list.appendChild(li);
  });
}


loadRanking();
loadBadges();

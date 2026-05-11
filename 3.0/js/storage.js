/**
 * storage.js — localStorage read/write encapsulation
 * All persistent state lives here.
 */

const Storage = (() => {
  const KEY = {
    USERS:       'carsale_users',
    CARS:        'carsale_cars',
    CURRENT:     'carsale_current_user',
    DEMO_DONE:   'carsale_demo_v3',
  };

  /* ---------- generic helpers ---------- */
  function read(key) {
    try {
      const raw = localStorage.getItem(key);
      return raw ? JSON.parse(raw) : null;
    } catch (_) { return null; }
  }

  function write(key, value) {
    localStorage.setItem(key, JSON.stringify(value));
  }

  /* ---------- users ---------- */
  function getUsers() {
    return read(KEY.USERS) || [];
  }

  function getUserByUsername(username) {
    return getUsers().find(u => u.username === username) || null;
  }

  function addUser(user) {
    const list = getUsers();
    list.push(user);
    write(KEY.USERS, list);
  }

  /* ---------- session ---------- */
  function getCurrentUser() {
    return read(KEY.CURRENT);
  }

  function setCurrentUser(user) {
    write(KEY.CURRENT, user);
  }

  function logout() {
    localStorage.removeItem(KEY.CURRENT);
  }

  /* ---------- cars ---------- */
  function getCars() {
    return read(KEY.CARS) || [];
  }

  function getCarById(id) {
    return getCars().find(c => c.id === id) || null;
  }

  function addCar(car) {
    const list = getCars();
    car.id = Date.now().toString(36) + Math.random().toString(36).slice(2, 6);
    car.createdAt = new Date().toISOString().slice(0, 10);
    list.push(car);
    write(KEY.CARS, list);
    return car;
  }

  /**
   * Search cars by model substring and/or exact year.
   * If both params are empty, returns all cars.
   */
  function searchCars({ model, year }) {
    let list = getCars();
    const q = (model || '').trim().toLowerCase();
    const y = parseInt(year, 10);

    if (q) {
      list = list.filter(c => c.model.toLowerCase().includes(q));
    }
    if (!isNaN(y) && y > 0) {
      list = list.filter(c => Number(c.year) === y);
    }
    return list;
  }

  /* ---------- demo seed ---------- */
  function initDemoData() {
    if (read(KEY.DEMO_DONE)) return;

    const demos = [
      { id: 'demo1', sellerId: 'demo', model: 'Toyota Camry',          year: 2022, color: 'Pearl White',   location: 'Beijing',   price: 185000, imageData: 'images/toyota-camry-2022.jpg',       imageName: 'toyota-camry-2022.jpg',       createdAt: '2024-03-01' },
      { id: 'demo2', sellerId: 'demo', model: 'Honda Civic',            year: 2021, color: 'Midnight Black', location: 'Shanghai',  price: 142000, imageData: 'images/honda-civic-2021.jpg',         imageName: 'honda-civic-2021.jpg',         createdAt: '2024-02-15' },
      { id: 'demo3', sellerId: 'demo', model: 'Tesla Model 3',          year: 2023, color: 'Solid White',   location: 'Shenzhen',  price: 268000, imageData: 'images/tesla-model-3-2023.jpg',       imageName: 'tesla-model-3-2023.jpg',       createdAt: '2024-03-10' },
      { id: 'demo4', sellerId: 'demo', model: 'BMW 3 Series',           year: 2020, color: 'Space Gray',    location: 'Guangzhou', price: 248000, imageData: 'images/bmw-3-series-2020.jpg',        imageName: 'bmw-3-series-2020.jpg',        createdAt: '2024-01-20' },
      { id: 'demo5', sellerId: 'demo', model: 'Volkswagen Passat',      year: 2019, color: 'Reflex Silver', location: 'Chengdu',   price: 138000, imageData: 'images/volkswagen-passat-2019.png',   imageName: 'volkswagen-passat-2019.png',   createdAt: '2024-01-05' },
      { id: 'demo6', sellerId: 'demo', model: 'Toyota Corolla',         year: 2018, color: 'Celestial Blue',location: 'Hangzhou',  price:  98000, imageData: 'images/toyota-corolla-2018.jpg',      imageName: 'toyota-corolla-2018.jpg',      createdAt: '2024-02-01' },
      { id: 'demo7', sellerId: 'demo', model: 'Mercedes-Benz C-Class',  year: 2022, color: 'Obsidian Black',location: 'Beijing',   price: 328000, imageData: 'images/mercedes-c-class-2022.png',    imageName: 'mercedes-c-class-2022.png',    createdAt: '2024-03-15' },
      { id: 'demo8', sellerId: 'demo', model: 'Audi A4',                year: 2021, color: 'Ibis White',   location: 'Shanghai',  price: 298000, imageData: 'images/audi-a4-2021.jpg',             imageName: 'audi-a4-2021.jpg',             createdAt: '2024-02-28' },
    ];

    write(KEY.CARS, demos);
    write(KEY.DEMO_DONE, true);
  }

  /* ---------- public API ---------- */
  return {
    getUsers, getUserByUsername, addUser,
    getCurrentUser, setCurrentUser, logout,
    getCars, getCarById, addCar, searchCars,
    initDemoData,
  };
})();

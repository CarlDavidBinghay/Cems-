<section id="home-section">
    <h1 class="text-3xl font-semibold text-gray-800 mb-6">Welcome to CEMS Dashboard</h1>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white p-6 rounded-2xl shadow hover:shadow-lg transition cursor-pointer stat-card" data-section="beneficiary">
            <h3 class="text-gray-600 text-sm">Total Beneficiaries</h3>
            <p class="text-3xl font-bold text-gray-800">1,234</p>
        </div>
        <div class="bg-white p-6 rounded-2xl shadow hover:shadow-lg transition cursor-pointer stat-card" data-section="program">
            <h3 class="text-gray-600 text-sm">Active Programs</h3>
            <p class="text-3xl font-bold text-gray-800">12</p>
        </div>
        <div class="bg-white p-6 rounded-2xl shadow hover:shadow-lg transition cursor-pointer stat-card" data-section="project">
            <h3 class="text-gray-600 text-sm">Ongoing Projects</h3>
            <p class="text-3xl font-bold text-gray-800">5</p>
        </div>
        <div class="bg-white p-6 rounded-2xl shadow hover:shadow-lg transition cursor-pointer stat-card" data-section="activity">
            <h3 class="text-gray-600 text-sm">Ongoing Activity</h3>
            <p class="text-3xl font-bold text-gray-800">10</p>
        </div>
        <div class="bg-white p-6 rounded-2xl shadow hover:shadow-lg transition cursor-pointer stat-card" data-section="partner">
            <h3 class="text-gray-600 text-sm">Partner & Donor Management</h3>
            <p class="text-3xl font-bold text-gray-800">1000</p>
        </div>
        <div class="bg-white p-6 rounded-2xl shadow hover:shadow-lg transition cursor-pointer stat-card" data-section="resource">
            <h3 class="text-gray-600 text-sm">Resources Management</h3>
            <p class="text-3xl font-bold text-gray-800">218</p>
        </div>
        <div class="bg-white p-6 rounded-2xl shadow hover:shadow-lg transition cursor-pointer stat-card" data-section="location">
            <h3 class="text-gray-600 text-sm">Location Management</h3>
            <p class="text-3xl font-bold text-gray-800">5324</p>
        </div>
        <div class="bg-white p-6 rounded-2xl shadow hover:shadow-lg transition cursor-pointer stat-card" data-section="coordinator">
            <h3 class="text-gray-600 text-sm">Coordinator / Staff / Volunteer</h3>
            <p class="text-3xl font-bold text-gray-800">501</p>
        </div>
        <div class="bg-white p-6 rounded-2xl shadow hover:shadow-lg transition cursor-pointer stat-card" data-section="evaluation">
            <h3 class="text-gray-600 text-sm">Evaluation & Monitoring</h3>
            <p class="text-3xl font-bold text-gray-800">1005</p>
        </div>
        <div class="bg-white p-6 rounded-2xl shadow hover:shadow-lg transition cursor-pointer stat-card" data-section="reports">
            <h3 class="text-gray-600 text-sm">Reports & Analytics</h3>
            <p class="text-3xl font-bold text-gray-800">3456</p>
        </div>
    </div>
</section>

<script>
// Stat card navigation
document.querySelectorAll('.stat-card').forEach(card => {
    card.addEventListener('click', () => {
        const section = card.dataset.section;
        // Navigate to the section
        const url = new URL(window.location);
        url.searchParams.set('section', section);
        window.location.href = url.toString();
    });
});
</script>
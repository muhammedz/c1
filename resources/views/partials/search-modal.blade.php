<!-- Arama Modal -->
<div id="searchModal" class="fixed inset-0 bg-black/50 z-50 hidden">
    <div class="flex items-start justify-center pt-20 px-4">
        <div class="w-full max-w-2xl bg-white rounded-xl shadow-2xl">
            <!-- Arama Başlığı ve Kapat -->
            <div class="flex items-center justify-between p-4 border-b">
                <h3 class="text-lg font-medium text-gray-800">Arama</h3>
                <button id="closeSearch" class="p-2 hover:bg-gray-100 rounded-lg transition-colors">
                    <span class="material-icons text-gray-400">close</span>
                </button>
            </div>

            <!-- Arama Formu -->
            <div class="p-4">
                <div class="relative">
                    <input type="text" placeholder="Ne aramıştınız?"
                        class="w-full pl-12 pr-4 py-4 bg-gray-50 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#004d2e] focus:border-transparent transition-all text-lg">
                    <span class="material-icons absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400 text-2xl">search</span>
                </div>

                <!-- Hızlı Aramalar -->
                <div class="mt-6">
                    <h4 class="text-sm font-medium text-gray-500 mb-3">Hızlı Aramalar</h4>
                    <div class="flex flex-wrap gap-2">
                        <button class="px-4 py-2 bg-gray-50 text-gray-600 rounded-lg hover:bg-gray-100 text-sm transition-colors">E-Belediye</button>
                        <button class="px-4 py-2 bg-gray-50 text-gray-600 rounded-lg hover:bg-gray-100 text-sm transition-colors">Online Ödeme</button>
                        <button class="px-4 py-2 bg-gray-50 text-gray-600 rounded-lg hover:bg-gray-100 text-sm transition-colors">Projeler</button>
                        <button class="px-4 py-2 bg-gray-50 text-gray-600 rounded-lg hover:bg-gray-100 text-sm transition-colors">İhaleler</button>
                    </div>
                </div>

                <!-- Popüler Aramalar -->
                <div class="mt-6">
                    <h4 class="text-sm font-medium text-gray-500 mb-3">Popüler Aramalar</h4>
                    <div class="space-y-3">
                        <a href="#" class="flex items-center space-x-3 p-2 hover:bg-gray-50 rounded-lg group">
                            <span class="material-icons text-gray-400 group-hover:text-[#004d2e]">trending_up</span>
                            <span class="text-gray-600 group-hover:text-[#004d2e]">Nöbetçi Eczaneler</span>
                        </a>
                        <a href="#" class="flex items-center space-x-3 p-2 hover:bg-gray-50 rounded-lg group">
                            <span class="material-icons text-gray-400 group-hover:text-[#004d2e]">trending_up</span>
                            <span class="text-gray-600 group-hover:text-[#004d2e]">Su Kesintileri</span>
                        </a>
                        <a href="#" class="flex items-center space-x-3 p-2 hover:bg-gray-50 rounded-lg group">
                            <span class="material-icons text-gray-400 group-hover:text-[#004d2e]">trending_up</span>
                            <span class="text-gray-600 group-hover:text-[#004d2e]">Otobüs Saatleri</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div> 
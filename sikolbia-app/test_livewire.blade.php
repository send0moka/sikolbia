<!DOCTYPE html>
<html>
<head>
    <title>Test Livewire Component</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-8">
    <div class="max-w-4xl mx-auto">
        <h1 class="text-2xl font-bold mb-4">Test Livewire Component</h1>
        
        <div class="bg-white p-6 rounded-lg shadow">
            <h2 class="text-lg font-semibold mb-4">Testing MapsBenihPupuk Component</h2>
            
            <div class="mb-4">
                <p><strong>Component Path:</strong> App\Livewire\Admin\BenihPupuk\MapsBenihPupuk</p>
                <p><strong>View Path:</strong> livewire.admin.benih-pupuk.maps-benih-pupuk</p>
            </div>
            
            <div class="border p-4 rounded">
                @livewire('admin.benih-pupuk.maps-benih-pupuk')
            </div>
        </div>
    </div>
    
    @livewireScripts
</body>
</html>

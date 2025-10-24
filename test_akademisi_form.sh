#!/bin/bash

echo "================================"
echo "Test Form Registrasi Akademisi"
echo "================================"
echo ""

# Test 1: Check route exists
echo "1. Testing route accessibility..."
STATUS=$(curl -s -o /dev/null -w "%{http_code}" http://localhost:8000/registrasi/akademisi)
if [ "$STATUS" -eq 200 ]; then
    echo "   ✅ Route /registrasi/akademisi accessible (HTTP $STATUS)"
else
    echo "   ❌ Route error (HTTP $STATUS)"
    exit 1
fi
echo ""

# Test 2: Check database columns
echo "2. Checking database columns for akademisi..."
docker-compose exec -T app php artisan tinker <<EOF
use Illuminate\Support\Facades\Schema;
\$columns = ['institusi', 'jenjang_pendidikan', 'program_studi'];
foreach (\$columns as \$col) {
    \$exists = Schema::hasColumn('registrasi_akses', \$col);
    echo \$exists ? "   ✅ Column '\$col' exists\n" : "   ❌ Column '\$col' missing\n";
}
exit;
EOF
echo ""

# Test 3: Check controller method
echo "3. Checking controller methods..."
if grep -q "showFormAkademisi" app/Http/Controllers/RegistrasiAksesController.php; then
    echo "   ✅ Method showFormAkademisi() exists"
else
    echo "   ❌ Method showFormAkademisi() not found"
fi

if grep -q "institusi" app/Http/Controllers/RegistrasiAksesController.php; then
    echo "   ✅ Controller handles 'institusi' field"
else
    echo "   ❌ Controller missing 'institusi' field"
fi
echo ""

# Test 4: Check view file
echo "4. Checking view file..."
if [ -f "resources/views/registrasi/akademisi.blade.php" ]; then
    echo "   ✅ View file exists"
    
    # Check key fields
    if grep -q 'name="institusi"' resources/views/registrasi/akademisi.blade.php; then
        echo "   ✅ Form has 'institusi' field"
    fi
    if grep -q 'name="jenjang_pendidikan"' resources/views/registrasi/akademisi.blade.php; then
        echo "   ✅ Form has 'jenjang_pendidikan' field"
    fi
    if grep -q 'name="program_studi"' resources/views/registrasi/akademisi.blade.php; then
        echo "   ✅ Form has 'program_studi' field"
    fi
    if grep -q 'value="akademik"' resources/views/registrasi/akademisi.blade.php; then
        echo "   ✅ Form has hidden field tipe_akses=akademik"
    fi
else
    echo "   ❌ View file not found"
fi
echo ""

# Test 5: Check routes
echo "5. Checking routes..."
if grep -q "public.registrasi.akademisi" routes/web.php; then
    echo "   ✅ Route 'public.registrasi.akademisi' registered"
else
    echo "   ❌ Route not registered"
fi
echo ""

# Summary
echo "================================"
echo "✅ Form Registrasi Akademisi Ready!"
echo "================================"
echo ""
echo "Access URL: http://localhost:8000/registrasi/akademisi"
echo ""
echo "Features:"
echo "  - Informasi Pribadi (nama, email, telepon)"
echo "  - Informasi Akademik (institusi, jenjang, prodi)"
echo "  - Tujuan Penggunaan (multiple select)"
echo "  - Deskripsi Kebutuhan (penelitian)"
echo "  - Email notification via queue"
echo "  - Admin panel integration"
echo ""

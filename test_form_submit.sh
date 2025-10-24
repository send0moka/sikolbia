#!/bin/bash

echo "================================"
echo "Test Form Submission via cURL"
echo "================================"
echo ""

# Step 1: Get form page and extract CSRF token
echo "1. Getting CSRF token from form..."
RESPONSE=$(curl -s -c /tmp/sikolbia_cookies.txt http://localhost:8000/registrasi/akademisi)

# Extract CSRF token from meta tag
TOKEN=$(echo "$RESPONSE" | grep -oP 'csrf-token" content="\K[^"]+' || echo "$RESPONSE" | grep -o 'csrf-token" content="[^"]*"' | sed 's/.*content="//;s/".*//')

if [ -z "$TOKEN" ]; then
    echo "   ❌ Failed to get CSRF token"
    echo "   Trying alternative method..."
    TOKEN=$(curl -s http://localhost:8000/registrasi/akademisi | grep -oP 'csrf-token" content="\K[^"]+')
fi

if [ -z "$TOKEN" ]; then
    echo "   ❌ Still failed to get CSRF token"
    exit 1
fi

echo "   ✅ CSRF Token: ${TOKEN:0:20}..."
echo ""

# Step 2: Submit form
echo "2. Submitting form data..."
SUBMIT_RESPONSE=$(curl -s -w "\nHTTP_CODE:%{http_code}" \
    -b /tmp/sikolbia_cookies.txt \
    -X POST http://localhost:8000/registrasi/proses \
    -H "Content-Type: application/x-www-form-urlencoded" \
    -H "X-CSRF-TOKEN: $TOKEN" \
    -H "Referer: http://localhost:8000/registrasi/akademisi" \
    -d "_token=$TOKEN" \
    -d "nama_lengkap=Test Akademisi via cURL" \
    -d "email=test.curl.$(date +%s)@university.ac.id" \
    -d "telepon=081234567890" \
    -d "tipe_akses=akademisi" \
    -d "institusi=Universitas Test" \
    -d "jenjang_pendidikan=S2" \
    -d "program_studi=Ilmu Komputer" \
    -d "tujuan_penggunaan[]=Penelitian Tesis" \
    -d "tujuan_penggunaan[]=Analisis Data" \
    -d "deskripsi_kebutuhan=Testing form submission via cURL")

# Extract HTTP code
HTTP_CODE=$(echo "$SUBMIT_RESPONSE" | grep "HTTP_CODE:" | cut -d':' -f2)
BODY=$(echo "$SUBMIT_RESPONSE" | sed '/HTTP_CODE:/d')

echo "   HTTP Status: $HTTP_CODE"
echo ""

# Check result
if [ "$HTTP_CODE" = "302" ] || [ "$HTTP_CODE" = "200" ]; then
    echo "   ✅ Form submitted successfully!"
    echo ""
    
    # Check database
    echo "3. Checking database..."
    docker-compose exec -T app php artisan tinker <<EOF
\$latest = \App\Models\RegistrasiAkses::latest()->first();
if (\$latest && str_contains(\$latest->nama_lengkap, 'cURL')) {
    echo "   ✅ Record found in database\n";
    echo "   ID: " . \$latest->id . "\n";
    echo "   Nama: " . \$latest->nama_lengkap . "\n";
    echo "   Email: " . \$latest->email . "\n";
    echo "   Institusi: " . \$latest->institusi . "\n";
    echo "   Status: " . \$latest->status . "\n";
} else {
    echo "   ❌ Record not found\n";
}
exit;
EOF
else
    echo "   ❌ Form submission failed!"
    echo ""
    echo "Response preview:"
    echo "$BODY" | head -20
fi

echo ""
echo "================================"
echo "Conclusion:"
echo "================================"

if [ "$HTTP_CODE" = "302" ] || [ "$HTTP_CODE" = "200" ]; then
    echo "✅ Server-side is working correctly!"
    echo ""
    echo "If browser still shows 419 error, the issue is:"
    echo "  1. Browser cookies/cache"
    echo "  2. Browser extensions blocking requests"
    echo "  3. Tab was opened too long (>120 min)"
    echo ""
    echo "Solutions:"
    echo "  - Clear browser cookies (F12 → Application → Cookies)"
    echo "  - Use Private/Incognito window"
    echo "  - Hard refresh (Ctrl+Shift+R)"
else
    echo "❌ Server-side issue detected"
    echo "Check logs: docker-compose logs app"
fi

echo ""

# Cleanup
rm -f /tmp/sikolbia_cookies.txt

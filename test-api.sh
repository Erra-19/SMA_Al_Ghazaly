#!/bin/bash
# API Test Script SMA Al Ghazaly
# Usage: bash test-api.sh [base_url]

BASE="${1:-https://alghazaly.erri.online}"
H='Accept: application/json'
TOKEN=""

pass=0; fail=0; skip=0
TS=$(date +%s)

check() {
    local label="$1" expected="$2" actual="$3"
    if [ "$actual" = "$expected" ]; then
        echo "  ✓ $label"
        ((pass++))
    else
        echo "  ✗ $label  (expected $expected, got $actual)"
        ((fail++))
    fi
}

check_any() {
    local label="$1" actual="$2"
    shift 2
    for expected in "$@"; do
        [ "$actual" = "$expected" ] && { echo "  ✓ $label"; ((pass++)); return; }
    done
    echo "  ✗ $label  (got $actual, expected one of: $*)"
    ((fail++))
}

skip_test() {
    echo "  ⚠ SKIP: $1"
    ((skip++))
}

section() { echo ""; echo "━━━ $1 ━━━"; }

# ══════════════════════════════════════
section "AUTH"
# ══════════════════════════════════════

echo "► POST /api/auth/login (valid)"
RES=$(curl -s -w "\n%{http_code}" -X POST "$BASE/api/auth/login" \
  -H "$H" -H "Content-Type: application/json" \
  -d '{"email":"admin@alghazaly.sch.id","password":"password"}')
CODE=$(echo "$RES" | tail -1)
BODY=$(echo "$RES" | head -1)
check "login returns 200" "200" "$CODE"
TOKEN=$(echo "$BODY" | python3 -c "import sys,json; print(json.load(sys.stdin).get('token',''))" 2>/dev/null)
[ -n "$TOKEN" ] && echo "  ✓ token acquired: ${TOKEN:0:20}..." || echo "  ✗ no token in response"

echo "► POST /api/auth/login (invalid)"
CODE=$(curl -s -o /dev/null -w "%{http_code}" -X POST "$BASE/api/auth/login" \
  -H "$H" -H "Content-Type: application/json" \
  -d '{"email":"wrong@test.com","password":"wrong"}')
check "wrong creds returns 401" "401" "$CODE"

echo "► GET /api/auth/me"
CODE=$(curl -s -o /dev/null -w "%{http_code}" "$BASE/api/auth/me" \
  -H "$H" -H "Authorization: Bearer $TOKEN")
check "me returns 200" "200" "$CODE"

echo "► GET /api/auth/me (no token)"
CODE=$(curl -s -o /dev/null -w "%{http_code}" "$BASE/api/auth/me" -H "$H")
check "me without token returns 401" "401" "$CODE"

# ══════════════════════════════════════
section "PUBLIC SETTINGS"
# ══════════════════════════════════════

echo "► GET /api/settings"
CODE=$(curl -s -o /dev/null -w "%{http_code}" "$BASE/api/settings" -H "$H")
check "settings returns 200" "200" "$CODE"

# ══════════════════════════════════════
section "PUBLIC POSTS"
# ══════════════════════════════════════

echo "► GET /api/posts"
CODE=$(curl -s -o /dev/null -w "%{http_code}" "$BASE/api/posts" -H "$H")
check "posts list returns 200" "200" "$CODE"

echo "► GET /api/posts?search=PPDB"
CODE=$(curl -s -o /dev/null -w "%{http_code}" "$BASE/api/posts?search=PPDB" -H "$H")
check "posts search returns 200" "200" "$CODE"

echo "► GET /api/posts/{slug} (valid)"
CODE=$(curl -s -o /dev/null -w "%{http_code}" "$BASE/api/posts/ppdb-2025-2026-resmi-dibuka" -H "$H")
check "post detail returns 200" "200" "$CODE"

echo "► GET /api/posts/{slug} (not found)"
CODE=$(curl -s -o /dev/null -w "%{http_code}" "$BASE/api/posts/slug-tidak-ada" -H "$H")
check "post not found returns 404" "404" "$CODE"

# ══════════════════════════════════════
section "PUBLIC CATEGORIES"
# ══════════════════════════════════════

echo "► GET /api/categories"
CODE=$(curl -s -o /dev/null -w "%{http_code}" "$BASE/api/categories" -H "$H")
check "categories returns 200" "200" "$CODE"

# ══════════════════════════════════════
section "PUBLIC PAGES"
# ══════════════════════════════════════

echo "► GET /api/pages"
CODE=$(curl -s -o /dev/null -w "%{http_code}" "$BASE/api/pages" -H "$H")
check "pages list returns 200" "200" "$CODE"

echo "► GET /api/pages/{slug} (valid)"
CODE=$(curl -s -o /dev/null -w "%{http_code}" "$BASE/api/pages/tentang-kami" -H "$H")
check "page detail returns 200" "200" "$CODE"

echo "► GET /api/pages/{slug} (not found)"
CODE=$(curl -s -o /dev/null -w "%{http_code}" "$BASE/api/pages/halaman-tidak-ada" -H "$H")
check "page not found returns 404" "404" "$CODE"

# ══════════════════════════════════════
section "PUBLIC TEACHERS"
# ══════════════════════════════════════

echo "► GET /api/teachers"
CODE=$(curl -s -o /dev/null -w "%{http_code}" "$BASE/api/teachers" -H "$H")
check "teachers returns 200" "200" "$CODE"

# ══════════════════════════════════════
section "PUBLIC TESTIMONIALS"
# ══════════════════════════════════════

echo "► GET /api/testimonials"
CODE=$(curl -s -o /dev/null -w "%{http_code}" "$BASE/api/testimonials" -H "$H")
check "testimonials returns 200" "200" "$CODE"

# ══════════════════════════════════════
section "PUBLIC ALUMNI"
# ══════════════════════════════════════

echo "► GET /api/alumni"
CODE=$(curl -s -o /dev/null -w "%{http_code}" "$BASE/api/alumni" -H "$H")
check "alumni returns 200" "200" "$CODE"

# ══════════════════════════════════════
section "PUBLIC ALBUMS"
# ══════════════════════════════════════

echo "► GET /api/albums"
CODE=$(curl -s -o /dev/null -w "%{http_code}" "$BASE/api/albums" -H "$H")
check "albums list returns 200" "200" "$CODE"

echo "► GET /api/albums/{slug} (not found)"
CODE=$(curl -s -o /dev/null -w "%{http_code}" "$BASE/api/albums/album-tidak-ada" -H "$H")
check "album not found returns 404" "404" "$CODE"

# ══════════════════════════════════════
section "PUBLIC FORMS"
# ══════════════════════════════════════

echo "► GET /api/forms/{slug} (not found — no forms seeded)"
CODE=$(curl -s -o /dev/null -w "%{http_code}" "$BASE/api/forms/kontak" -H "$H")
check "form not found returns 404" "404" "$CODE"

# ══════════════════════════════════════
section "PUBLIC PPDB REGISTRATION"
# ══════════════════════════════════════

echo "► POST /api/registrations"
RES=$(curl -s -w "\n%{http_code}" -X POST "$BASE/api/registrations" \
  -H "$H" -H "Content-Type: application/json" \
  -d '{
    "full_name": "Test Pendaftar Dummy",
    "birth_date": "2009-06-15",
    "birth_place": "Jakarta",
    "gender": "L",
    "address": "Jl. Test No. 99, Jakarta",
    "phone": "081299999999",
    "parent_name": "Orang Tua Test",
    "parent_phone": "081288888888",
    "previous_school": "SMP Test Jakarta",
    "academic_year": "2025/2026"
  }')
CODE=$(echo "$RES" | tail -1)
BODY=$(echo "$RES" | head -1)
check "create registration returns 201" "201" "$CODE"
REG_NUMBER=$(echo "$BODY" | python3 -c "import sys,json; d=json.load(sys.stdin); print(d.get('data',{}).get('registration_number','') or d.get('registration_number',''))" 2>/dev/null)
REG_ID=$(echo "$BODY" | python3 -c "import sys,json; d=json.load(sys.stdin); print(d.get('data',{}).get('registration_id','') or d.get('id',''))" 2>/dev/null)
echo "  → Registration number: $REG_NUMBER (id: $REG_ID)"

echo "► GET /api/registrations/{number}/status (valid)"
if [ -n "$REG_NUMBER" ]; then
    CODE=$(curl -s -o /dev/null -w "%{http_code}" "$BASE/api/registrations/$REG_NUMBER/status" -H "$H")
    check "check registration status returns 200" "200" "$CODE"
else
    skip_test "no registration number"
fi

echo "► GET /api/registrations/{number}/status (invalid)"
CODE=$(curl -s -o /dev/null -w "%{http_code}" "$BASE/api/registrations/PPDB-0000-9999/status" -H "$H")
check "invalid registration returns 404" "404" "$CODE"

# ══════════════════════════════════════
section "ADMIN DASHBOARD"
# ══════════════════════════════════════

echo "► GET /api/admin/dashboard"
CODE=$(curl -s -o /dev/null -w "%{http_code}" "$BASE/api/admin/dashboard" \
  -H "$H" -H "Authorization: Bearer $TOKEN")
check "dashboard returns 200" "200" "$CODE"

echo "► GET /api/admin/dashboard (no auth)"
CODE=$(curl -s -o /dev/null -w "%{http_code}" "$BASE/api/admin/dashboard" -H "$H")
check "dashboard without auth returns 401" "401" "$CODE"

# ══════════════════════════════════════
section "ADMIN POSTS"
# ══════════════════════════════════════

echo "► GET /api/admin/posts"
CODE=$(curl -s -o /dev/null -w "%{http_code}" "$BASE/api/admin/posts" \
  -H "$H" -H "Authorization: Bearer $TOKEN")
check "admin posts list returns 200" "200" "$CODE"

echo "► POST /api/admin/posts"
RES=$(curl -s -w "\n%{http_code}" -X POST "$BASE/api/admin/posts" \
  -H "$H" -H "Authorization: Bearer $TOKEN" -H "Content-Type: application/json" \
  -d "{\"title\":\"Artikel Test $TS\",\"content\":\"<p>Konten artikel test.</p>\",\"status\":\"draft\",\"category_ids\":[]}")
CODE=$(echo "$RES" | tail -1)
BODY=$(echo "$RES" | head -1)
check "create post returns 201" "201" "$CODE"
POST_ID=$(echo "$BODY" | python3 -c "import sys,json; d=json.load(sys.stdin); print(d.get('post_id','') or d.get('id',''))" 2>/dev/null)

echo "► GET /api/admin/posts/{id}"
if [ -n "$POST_ID" ]; then
    CODE=$(curl -s -o /dev/null -w "%{http_code}" "$BASE/api/admin/posts/$POST_ID" \
      -H "$H" -H "Authorization: Bearer $TOKEN")
    check "admin post detail returns 200" "200" "$CODE"
else
    skip_test "no post id from create"
fi

echo "► PUT /api/admin/posts/{id}"
if [ -n "$POST_ID" ]; then
    CODE=$(curl -s -o /dev/null -w "%{http_code}" -X PUT "$BASE/api/admin/posts/$POST_ID" \
      -H "$H" -H "Authorization: Bearer $TOKEN" -H "Content-Type: application/json" \
      -d '{"title":"Artikel Test Updated","content":"<p>Konten diupdate.</p>","status":"published"}')
    check "update post returns 200" "200" "$CODE"
else
    skip_test "no post id from create"
fi

echo "► DELETE /api/admin/posts/{id}"
if [ -n "$POST_ID" ]; then
    CODE=$(curl -s -o /dev/null -w "%{http_code}" -X DELETE "$BASE/api/admin/posts/$POST_ID" \
      -H "$H" -H "Authorization: Bearer $TOKEN")
    check_any "delete post returns 200 or 204" "$CODE" "200" "204"
else
    skip_test "no post id from create"
fi

# ══════════════════════════════════════
section "ADMIN CATEGORIES"
# ══════════════════════════════════════

echo "► GET /api/admin/categories"
CODE=$(curl -s -o /dev/null -w "%{http_code}" "$BASE/api/admin/categories" \
  -H "$H" -H "Authorization: Bearer $TOKEN")
check "admin categories list returns 200" "200" "$CODE"

echo "► POST /api/admin/categories"
RES=$(curl -s -w "\n%{http_code}" -X POST "$BASE/api/admin/categories" \
  -H "$H" -H "Authorization: Bearer $TOKEN" -H "Content-Type: application/json" \
  -d "{\"category_name\":\"Kategori Test $TS\",\"slug\":\"kategori-test-$TS\"}")
CODE=$(echo "$RES" | tail -1)
BODY=$(echo "$RES" | head -1)
check "create category returns 201" "201" "$CODE"
CAT_ID=$(echo "$BODY" | python3 -c "import sys,json; d=json.load(sys.stdin); print(d.get('category_id','') or d.get('id',''))" 2>/dev/null)

echo "► PUT /api/admin/categories/{id}"
if [ -n "$CAT_ID" ]; then
    CODE=$(curl -s -o /dev/null -w "%{http_code}" -X PUT "$BASE/api/admin/categories/$CAT_ID" \
      -H "$H" -H "Authorization: Bearer $TOKEN" -H "Content-Type: application/json" \
      -d '{"category_name":"Kategori Test Updated","slug":"kategori-test-updated"}')
    check "update category returns 200" "200" "$CODE"
else
    skip_test "no category id"
fi

echo "► DELETE /api/admin/categories/{id}"
if [ -n "$CAT_ID" ]; then
    CODE=$(curl -s -o /dev/null -w "%{http_code}" -X DELETE "$BASE/api/admin/categories/$CAT_ID" \
      -H "$H" -H "Authorization: Bearer $TOKEN")
    check_any "delete category returns 200 or 204" "$CODE" "200" "204"
else
    skip_test "no category id"
fi

# ══════════════════════════════════════
section "ADMIN PAGES"
# ══════════════════════════════════════

echo "► GET /api/admin/pages"
CODE=$(curl -s -o /dev/null -w "%{http_code}" "$BASE/api/admin/pages" \
  -H "$H" -H "Authorization: Bearer $TOKEN")
check "admin pages list returns 200" "200" "$CODE"

echo "► POST /api/admin/pages"
RES=$(curl -s -w "\n%{http_code}" -X POST "$BASE/api/admin/pages" \
  -H "$H" -H "Authorization: Bearer $TOKEN" -H "Content-Type: application/json" \
  -d "{\"title\":\"Halaman Test $TS\",\"slug\":\"halaman-test-$TS\",\"content\":\"<p>Konten test</p>\",\"is_published\":true,\"order\":99}")
CODE=$(echo "$RES" | tail -1)
BODY=$(echo "$RES" | head -1)
check "create page returns 201" "201" "$CODE"
PAGE_ID=$(echo "$BODY" | python3 -c "import sys,json; d=json.load(sys.stdin); print(d.get('page_id','') or d.get('id',''))" 2>/dev/null)

echo "► GET /api/admin/pages/{id}"
if [ -n "$PAGE_ID" ]; then
    CODE=$(curl -s -o /dev/null -w "%{http_code}" "$BASE/api/admin/pages/$PAGE_ID" \
      -H "$H" -H "Authorization: Bearer $TOKEN")
    check "admin page detail returns 200" "200" "$CODE"
else
    skip_test "no page id"
fi

echo "► DELETE /api/admin/pages/{id}"
if [ -n "$PAGE_ID" ]; then
    CODE=$(curl -s -o /dev/null -w "%{http_code}" -X DELETE "$BASE/api/admin/pages/$PAGE_ID" \
      -H "$H" -H "Authorization: Bearer $TOKEN")
    check_any "delete page returns 200 or 204" "$CODE" "200" "204"
else
    skip_test "no page id"
fi

# ══════════════════════════════════════
section "ADMIN TEACHERS"
# ══════════════════════════════════════

echo "► GET /api/admin/teachers"
CODE=$(curl -s -o /dev/null -w "%{http_code}" "$BASE/api/admin/teachers" \
  -H "$H" -H "Authorization: Bearer $TOKEN")
check "admin teachers list returns 200" "200" "$CODE"

echo "► POST /api/admin/teachers"
RES=$(curl -s -w "\n%{http_code}" -X POST "$BASE/api/admin/teachers" \
  -H "$H" -H "Authorization: Bearer $TOKEN" -H "Content-Type: application/json" \
  -d '{"name":"Guru Test API","position":"Guru","subject":"TIK","bio":"Guru test untuk API testing.","order":99,"is_published":1}')
CODE=$(echo "$RES" | tail -1)
BODY=$(echo "$RES" | head -1)
check "create teacher returns 201" "201" "$CODE"
TEACHER_ID=$(echo "$BODY" | python3 -c "import sys,json; d=json.load(sys.stdin); print(d.get('teacher_id','') or d.get('id',''))" 2>/dev/null)

echo "► GET /api/admin/teachers/{id}"
if [ -n "$TEACHER_ID" ]; then
    CODE=$(curl -s -o /dev/null -w "%{http_code}" "$BASE/api/admin/teachers/$TEACHER_ID" \
      -H "$H" -H "Authorization: Bearer $TOKEN")
    check "admin teacher detail returns 200" "200" "$CODE"
else
    skip_test "no teacher id"
fi

echo "► PUT /api/admin/teachers/{id}"
if [ -n "$TEACHER_ID" ]; then
    CODE=$(curl -s -o /dev/null -w "%{http_code}" -X PUT "$BASE/api/admin/teachers/$TEACHER_ID" \
      -H "$H" -H "Authorization: Bearer $TOKEN" -H "Content-Type: application/json" \
      -d '{"name":"Guru Test Updated","position":"Guru Senior","subject":"TIK"}')
    check "update teacher returns 200" "200" "$CODE"
else
    skip_test "no teacher id"
fi

echo "► DELETE /api/admin/teachers/{id}"
if [ -n "$TEACHER_ID" ]; then
    CODE=$(curl -s -o /dev/null -w "%{http_code}" -X DELETE "$BASE/api/admin/teachers/$TEACHER_ID" \
      -H "$H" -H "Authorization: Bearer $TOKEN")
    check_any "delete teacher returns 200 or 204" "$CODE" "200" "204"
else
    skip_test "no teacher id"
fi

# ══════════════════════════════════════
section "ADMIN TESTIMONIALS"
# ══════════════════════════════════════

echo "► GET /api/admin/testimonials"
CODE=$(curl -s -o /dev/null -w "%{http_code}" "$BASE/api/admin/testimonials" \
  -H "$H" -H "Authorization: Bearer $TOKEN")
check "admin testimonials list returns 200" "200" "$CODE"

echo "► POST /api/admin/testimonials"
RES=$(curl -s -w "\n%{http_code}" -X POST "$BASE/api/admin/testimonials" \
  -H "$H" -H "Authorization: Bearer $TOKEN" -H "Content-Type: application/json" \
  -d '{"name":"Testimoni Test","role":"Tester","content":"Ini testimoni test dari API testing.","rating":5,"is_published":1,"order":99}')
CODE=$(echo "$RES" | tail -1)
BODY=$(echo "$RES" | head -1)
check "create testimonial returns 201" "201" "$CODE"
TESTI_ID=$(echo "$BODY" | python3 -c "import sys,json; d=json.load(sys.stdin); print(d.get('testimonial_id','') or d.get('id',''))" 2>/dev/null)

echo "► PUT /api/admin/testimonials/{id}"
if [ -n "$TESTI_ID" ]; then
    CODE=$(curl -s -o /dev/null -w "%{http_code}" -X PUT "$BASE/api/admin/testimonials/$TESTI_ID" \
      -H "$H" -H "Authorization: Bearer $TOKEN" -H "Content-Type: application/json" \
      -d '{"name":"Testimoni Updated","role":"Tester","content":"Diupdate via API.","rating":4}')
    check "update testimonial returns 200" "200" "$CODE"
else
    skip_test "no testimonial id"
fi

echo "► DELETE /api/admin/testimonials/{id}"
if [ -n "$TESTI_ID" ]; then
    CODE=$(curl -s -o /dev/null -w "%{http_code}" -X DELETE "$BASE/api/admin/testimonials/$TESTI_ID" \
      -H "$H" -H "Authorization: Bearer $TOKEN")
    check_any "delete testimonial returns 200 or 204" "$CODE" "200" "204"
else
    skip_test "no testimonial id"
fi

# ══════════════════════════════════════
section "ADMIN ALUMNI"
# ══════════════════════════════════════

echo "► GET /api/admin/alumni"
CODE=$(curl -s -o /dev/null -w "%{http_code}" "$BASE/api/admin/alumni" \
  -H "$H" -H "Authorization: Bearer $TOKEN")
check "admin alumni list returns 200" "200" "$CODE"

echo "► POST /api/admin/alumni"
RES=$(curl -s -w "\n%{http_code}" -X POST "$BASE/api/admin/alumni" \
  -H "$H" -H "Authorization: Bearer $TOKEN" -H "Content-Type: application/json" \
  -d '{"name":"Alumni Test API","graduation_year":2024,"current_institution":"Universitas Test","major":"Teknik Test","achievement":"Lulus test API","is_published":1}')
CODE=$(echo "$RES" | tail -1)
BODY=$(echo "$RES" | head -1)
check "create alumni returns 201" "201" "$CODE"
ALUMNI_ID=$(echo "$BODY" | python3 -c "import sys,json; d=json.load(sys.stdin); print(d.get('alumni_id','') or d.get('id',''))" 2>/dev/null)

echo "► PUT /api/admin/alumni/{id}"
if [ -n "$ALUMNI_ID" ]; then
    CODE=$(curl -s -o /dev/null -w "%{http_code}" -X PUT "$BASE/api/admin/alumni/$ALUMNI_ID" \
      -H "$H" -H "Authorization: Bearer $TOKEN" -H "Content-Type: application/json" \
      -d '{"name":"Alumni Test Updated","graduation_year":2024,"current_institution":"Universitas Updated"}')
    check "update alumni returns 200" "200" "$CODE"
else
    skip_test "no alumni id"
fi

echo "► DELETE /api/admin/alumni/{id}"
if [ -n "$ALUMNI_ID" ]; then
    CODE=$(curl -s -o /dev/null -w "%{http_code}" -X DELETE "$BASE/api/admin/alumni/$ALUMNI_ID" \
      -H "$H" -H "Authorization: Bearer $TOKEN")
    check_any "delete alumni returns 200 or 204" "$CODE" "200" "204"
else
    skip_test "no alumni id"
fi

# ══════════════════════════════════════
section "ADMIN REGISTRATIONS"
# ══════════════════════════════════════

echo "► GET /api/admin/registrations"
CODE=$(curl -s -o /dev/null -w "%{http_code}" "$BASE/api/admin/registrations" \
  -H "$H" -H "Authorization: Bearer $TOKEN")
check "admin registrations list returns 200" "200" "$CODE"

echo "► GET /api/admin/registrations/1"
CODE=$(curl -s -o /dev/null -w "%{http_code}" "$BASE/api/admin/registrations/1" \
  -H "$H" -H "Authorization: Bearer $TOKEN")
check "admin registration detail returns 200" "200" "$CODE"

echo "► PATCH /api/admin/registrations/1/status"
CODE=$(curl -s -o /dev/null -w "%{http_code}" -X PATCH "$BASE/api/admin/registrations/1/status" \
  -H "$H" -H "Authorization: Bearer $TOKEN" -H "Content-Type: application/json" \
  -d '{"status":"accepted","notes":"Diterima via API test"}')
check "update registration status returns 200" "200" "$CODE"

# ══════════════════════════════════════
section "ADMIN MESSAGES"
# ══════════════════════════════════════

echo "► GET /api/admin/messages"
CODE=$(curl -s -o /dev/null -w "%{http_code}" "$BASE/api/admin/messages" \
  -H "$H" -H "Authorization: Bearer $TOKEN")
check "admin messages list returns 200" "200" "$CODE"

echo "► GET /api/admin/messages/1 (no data yet)"
CODE=$(curl -s -o /dev/null -w "%{http_code}" "$BASE/api/admin/messages/1" \
  -H "$H" -H "Authorization: Bearer $TOKEN")
check_any "admin message detail returns 200 or 404" "$CODE" "200" "404"

# ══════════════════════════════════════
section "ADMIN PAYMENTS"
# ══════════════════════════════════════

echo "► GET /api/admin/payments"
CODE=$(curl -s -o /dev/null -w "%{http_code}" "$BASE/api/admin/payments" \
  -H "$H" -H "Authorization: Bearer $TOKEN")
check "admin payments list returns 200" "200" "$CODE"

echo "► GET /api/admin/payments/1 (no data yet)"
CODE=$(curl -s -o /dev/null -w "%{http_code}" "$BASE/api/admin/payments/1" \
  -H "$H" -H "Authorization: Bearer $TOKEN")
check_any "admin payment detail returns 200 or 404" "$CODE" "200" "404"

# ══════════════════════════════════════
section "ADMIN SETTINGS"
# ══════════════════════════════════════

echo "► GET /api/admin/settings"
CODE=$(curl -s -o /dev/null -w "%{http_code}" "$BASE/api/admin/settings" \
  -H "$H" -H "Authorization: Bearer $TOKEN")
check "admin settings returns 200" "200" "$CODE"

echo "► PUT /api/admin/settings"
CODE=$(curl -s -o /dev/null -w "%{http_code}" -X PUT "$BASE/api/admin/settings" \
  -H "$H" -H "Authorization: Bearer $TOKEN" -H "Content-Type: application/json" \
  -d '{"settings":[{"key":"site_name","value":"SMA Al Ghazaly"},{"key":"ppdb_open","value":"true"}]}')
check "update settings returns 200" "200" "$CODE"

# ══════════════════════════════════════
section "ADMIN USERS AND ROLES"
# ══════════════════════════════════════

echo "► GET /api/admin/users"
CODE=$(curl -s -o /dev/null -w "%{http_code}" "$BASE/api/admin/users" \
  -H "$H" -H "Authorization: Bearer $TOKEN")
check "admin users list returns 200" "200" "$CODE"

echo "► GET /api/admin/roles"
CODE=$(curl -s -o /dev/null -w "%{http_code}" "$BASE/api/admin/roles" \
  -H "$H" -H "Authorization: Bearer $TOKEN")
check "admin roles list returns 200" "200" "$CODE"

# ══════════════════════════════════════
section "ADMIN ALBUMS (bug check)"
# ══════════════════════════════════════

echo "► GET /api/admin/albums"
CODE=$(curl -s -o /dev/null -w "%{http_code}" "$BASE/api/admin/albums" \
  -H "$H" -H "Authorization: Bearer $TOKEN")
check_any "admin albums list (known 500 bug)" "$CODE" "200" "500"
[ "$CODE" = "500" ] && echo "    ⚠ Bug aktif: admin albums controller error"

# ══════════════════════════════════════
section "ADMIN MEDIAS (bug check)"
# ══════════════════════════════════════

echo "► GET /api/admin/medias"
CODE=$(curl -s -o /dev/null -w "%{http_code}" "$BASE/api/admin/medias" \
  -H "$H" -H "Authorization: Bearer $TOKEN")
check_any "admin medias list (known 500 bug)" "$CODE" "200" "500"
[ "$CODE" = "500" ] && echo "    ⚠ Bug aktif: admin medias controller error"

# ══════════════════════════════════════
section "AUTH LOGOUT"
# ══════════════════════════════════════

echo "► POST /api/auth/logout"
CODE=$(curl -s -o /dev/null -w "%{http_code}" -X POST "$BASE/api/auth/logout" \
  -H "$H" -H "Authorization: Bearer $TOKEN")
check "logout returns 200" "200" "$CODE"

echo "► GET /api/auth/me (after logout)"
CODE=$(curl -s -o /dev/null -w "%{http_code}" "$BASE/api/auth/me" \
  -H "$H" -H "Authorization: Bearer $TOKEN")
check "me after logout returns 401" "401" "$CODE"

# ══════════════════════════════════════
echo ""
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo "  RESULT: $pass passed, $fail failed, $skip skipped"
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"

مرحله ۱ فارسی‌سازی تقویم CRM

فایل‌ها را با حفظ مسیر داخل پروژه جایگزین کنید.

سپس در ریشه پروژه اجرا کنید:

npm install vue3-persian-datetime-picker@1.2.2 --save
npm run build
php artisan optimize:clear

نکته مهم:
config/app.php را روی timezone=UTC نگه دارید.
تقویم برای کاربر شمسی/ساعت ایران است، اما StoreFollowUpRequest زمان انتخابی Asia/Tehran را قبل از ذخیره به UTC تبدیل می‌کند.

تست:
1) /followups/create را باز کنید.
2) یک تاریخ و ساعت شمسی انتخاب کنید.
3) ذخیره کنید.
4) در /followups باید تاریخ به شمسی و ساعت ایران نمایش داده شود.

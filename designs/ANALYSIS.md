# KDMS Template Analysis Report

## Summary

- Total HTML templates in ZIP: **18**
- Remote image/logo URLs found: **11**
- ZIP contains HTML only (no separate logo/font binary files). Assets are linked via CDN/remote URLs.

## By Document Type
- `catalog`: 1
- `contract`: 4
- `invoice`: 1
- `quotation`: 6
- `receipt`: 2
- `report`: 4

## Remote Assets (logos / seals / signatures / photos)
- `https://sns-interior.com/khtm-swimming.png`
- `https://www.rukn-eltatawer.com/khtm-swimming.png`
- `https://www.rukn-eltatawer.com/khtm.webp`
- `https://www.rukn-eltatawer.com/s1.jpeg`
- `https://www.rukn-eltatawer.com/s2.jpeg`
- `https://www.rukn-eltatawer.com/s3.webp`
- `https://www.rukn-eltatawer.com/s4.jpg`
- `https://www.rukn-eltatawer.com/s5.jpeg`
- `https://www.rukn-eltatawer.com/s7.webp`
- `https://www.rukn-eltatawer.com/sign-landscaping.webp`
- `https://www.rukn-eltatawer.com/sign.webp`

## Dynamic Fields by Type
### invoice
- اسم العميل
- تاريخ الفاتورة
- عنوان العميل

### receipt
- اسم العميل / الدافع
- العميل / الدافع
- تاريخ الاستلام
- تاريخ السداد
- عنوان العميل
- عنوان المشروع

## Templates Detail
### ret-lnd-rcp-0827.html (receipt / ar)
- Title: سند قبض — ركن التطور لتنسيق الحدائق | خادم الرميثي
- Items table: False | Signatures: True
- Key bits: `{"co-name": ["ركن التطور لتنسيق الحدائق ذ.م.م"], "co-name-en": ["Rukn El-Tatawer for Landscaping LLC"], "db-num": ["RET-LND-RCP-0619"], "title-h1": ["سند قبض"], "written": ["المبلغ كتابةً: ألفا درهم إماراتي لا غير"], "pm-val": ["نقداً — كاش"]}`
- Labeled fields:
  - **اسم العميل / الدافع** → `خادم الرميثي`
  - **تاريخ الاستلام** → `الجمعة، 19 يونيو 2026`
  - **عنوان العميل** → `فيلا 12، شخبوط، أبوظبي`
- Brand assets:
  - https://www.rukn-eltatawer.com/sign-landscaping.webp

### rec7822.html (receipt / ar)
- Title: سند قبض — الدفعة الأولى 75% | ركن التطور
- Items table: False | Signatures: True
- Key bits: `{"co-name": ["ركن التطور لأنظمة العزل الحديث ذ.م.م"], "co-name-en": ["Rukn El-Tatawer for Modern Insulation Systems LLC"], "db-num": ["RET-RCP-2026-0609"], "title-h1": ["سند قبض — الدفعة الأولى"], "big-amount": ["6,000"], "written": ["المبلغ كتابة: &nbsp;ستة آلاف درهم إماراتي فقط لا غير"], "pm-val":`
- Labeled fields:
  - **العميل / الدافع** → `عادل عمار على سالم`
  - **تاريخ السداد** → `الخميس، 11 يونيو 2026`
  - **عنوان المشروع** → `فيلا 23b، الإحداثيات 25.238178,55.357197، أبوظبي`
- Brand assets:
  - https://www.rukn-eltatawer.com/khtm.webp
  - https://www.rukn-eltatawer.com/sign.webp

### ret-lnd-inv-0619.html (invoice / ar)
- Title: فاتورة — ركن التطور لتنسيق الحدائق | خادم الرميثي
- Items table: True | Signatures: True
- Key bits: `{"co-name": ["ركن التطور لتنسيق الحدائق ذ.م.م"], "co-name-en": ["Rukn El-Tatawer for Landscaping LLC"], "db-num": ["RET-LND-INV-0619"], "title-h1": ["فاتورة أعمال تنسيق الحدائق"], "grand-num": ["2,000 درهم إماراتي"], "written": ["المبلغ كتابةً: ألفا درهم إماراتي لا غير"]}`
- Labeled fields:
  - **اسم العميل** → `خادم الرميثي`
  - **تاريخ الفاتورة** → `الجمعة، 19 يونيو 2026`
  - **عنوان العميل** → `فيلا 12، شخبوط، أبوظبي`
- Table headers: #, وصف الأعمال, الكمية, سعر الوحدة, الإجمالي
- Brand assets:
  - https://www.rukn-eltatawer.com/sign-landscaping.webp

### quotation-irrigation-ar.html (quotation / ar)
- Title: عرض سعر - شبكة ري متكاملة | ركن التطور - العين
- Items table: True | Signatures: False
- Table headers: الخيار, المواصفات التقنية, الإجمالي (درهم), الخيار, المواصفات التقنية, الإجمالي (درهم)

### contract-jamal-alnaimi.html (contract / ar)
- Title: عقد أعمال عزل سطح جراج سيارات — جمال النعيمي | ركن التطور
- Items table: True | Signatures: True
- Table headers: #, البند, المواصفة, النسبة, الدفعة, الاستحقاق, المبلغ / التاريخ
- Brand assets:
  - https://www.rukn-eltatawer.com/khtm.webp

### contract-pool-khalil-hosni.html (contract / ar)
- Title: عقد صيانة دورية للمسبح — ركن التطور لأحواض السباحة
- Items table: False | Signatures: True
- Key bits: `{"db-num": ["RET-POOL-2026-0625"]}`
- Brand assets:
  - https://sns-interior.com/khtm-swimming.png

### contract-pool-hassan-hashmi.html (contract / ar)
- Title: عقد صيانة دورية للمسبح — ركن التطور لأحواض السباحة
- Items table: False | Signatures: True
- Key bits: `{"db-num": ["RET-POOL-2026-0707"]}`
- Brand assets:
  - https://sns-interior.com/khtm-swimming.png

### rukn-coolroof-quote.html (quotation / ar)
- Title: عرض سعر - Roof Protection | ركن التطور - أبوظبي
- Items table: True | Signatures: True
- Table headers: الخدمة, نسبة العزل الحراري, سعر المتر, الإجمالي (120 م²)

### quote-jamal-alnaimi.html (quotation / ar)
- Title: عرض سعر - عزل سطح جراج Cool Roof | ركن التطور - عجمان
- Items table: True | Signatures: False
- Table headers: عدد الطبقات, نسبة العزل الحراري, سعر المتر, الإجمالي (55 م²)

### quotation-coolroof-auh-ar.html (quotation / ar)
- Title: عرض سعر - عزل Cool Roof | ركن التطور - أبوظبي
- Items table: True | Signatures: False
- Table headers: عدد الطبقات, نسبة العزل الحراري, سعر المتر, الإجمالي (120 م²), عدد الطبقات, نسبة العزل الحراري, سعر المتر, الإجمالي (120 م²)

### re743.html (report / en)
- Title: Technical Inspection Report — Rukn El-Tatawer | Pradeepkumar | Reem Island
- Items table: True | Signatures: True
- Key bits: `{"co-name": ["Rukn El-Tatawer for Water Leak Detection, Treatment & Building Maintenance LLC"]}`
- Table headers: Criterion, Solution 1 (Traditional), Solution 2 (Injection) ⭐

### qu-2026-0714.html (quotation / ar)
- Title: عرض سعر - عزل فيبر مرن ( المطاطي ) | ركن التطور للعوازل وكشف التسربات ذ.م.م
- Items table: True | Signatures: False
- Table headers: عدد الطبقات, نسبة العزل الحراري, سعر المتر, الإجمالي (720 م²), عدد الطبقات, نسبة العزل الحراري, سعر المتر, الإجمالي (720 م²)

### ret-leak-2026-0714.html (report / ar)
- Title: تقرير كشف تسربات المياه | ركن التطور للعوازل وكشف التسربات ذ.م.م
- Items table: False | Signatures: True

### contract-pool-afnan-hassan.html (contract / ar)
- Title: عقد توريد وتوصيل غطاء مسبح — أفنان حسن | ركن التطور
- Items table: False | Signatures: True
- Brand assets:
  - https://www.rukn-eltatawer.com/s2.jpeg
  - https://www.rukn-eltatawer.com/s1.jpeg
  - https://www.rukn-eltatawer.com/s2.jpeg
  - https://www.rukn-eltatawer.com/s5.jpeg
  - https://www.rukn-eltatawer.com/s7.webp
  - https://www.rukn-eltatawer.com/s3.webp
  - https://www.rukn-eltatawer.com/s4.jpg
  - https://www.rukn-eltatawer.com/khtm-swimming.png

### RET-LEAK-2026-0714-EN.html (report / en)
- Title: Water Leak Detection Report | Rukn Eltatawer Insulation & Leak Detection L.L.C
- Items table: False | Signatures: True

### inspection-report-form.html (report / ar)
- Title: تقرير فحص مبنى قبل الشراء | ركن التطور للخدمات الهندسية المتكاملة
- Items table: False | Signatures: True

### cool-roof-catalog-rukn.html (catalog / ar)
- Title: كتالوج كول رووف 2026 | ركن التطور
- Items table: False | Signatures: True

### quote-06028-1.html (quotation / ar)
- Title: عرض سعر - عزل Cool Roof | ركن التطور - دبي
- Items table: True | Signatures: False
- Table headers: عدد الطبقات, الضمان, سعر المتر, السعر الأصلي, بعد الخصم 🔥, عدد الطبقات, الضمان, سعر المتر, السعر الأصلي, بعد الخصم 🔥

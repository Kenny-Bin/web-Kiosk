# Web Kiosk - 셀프 접수 시스템

웹 기반 키오스크 시스템입니다. 터치스크린 환경에 최적화된 직관적인 인터페이스를 제공합니다.

## 🏥 프로젝트 개요

Web Kiosk는 병원 및 의료기관에서 환자의 자가 접수를 지원하는 키오스크 애플리케이션입니다. 신규 환자 등록부터 재진 환자의 접수, 진료 정보 입력까지 전체 접수 프로세스를 디지털화합니다.

### 주요 기능

- ✅ **환자 구분**: 초진/재진 환자 자동 구분
- 🔍 **환자 조회**: 이름, 전화번호, 생년월일, 주민등록번호로 검색
- 📝 **정보 입력**: 환자 기본정보, 주소, 연락처 등록
- 🏥 **진료 정보**: 진료 분류, 내원 경로 선택
- 📋 **접수 처리**: 차트 생성, 접수증 발행
- 🔐 **개인정보 보호**: 주민등록번호 AES-128-CBC 암호화

## 🛠 기술 스택

### Backend
- **Framework**: CodeIgniter 4
- **Language**: PHP 7+
- **Database**: MySQL / MariaDB
- **Authentication**: Firebase JWT
- **Encryption**: OpenSSL (AES-128-CBC)

### Frontend
- **JavaScript**: jQuery 1.12.4
- **UI Library**: jQuery UI
- **CSS**: Custom Responsive Grid (Flexbox)
- **Address API**: Daum Postcode API v2

### Package Manager
- **Composer**: PHP 의존성 관리
- 
## 🚀 설치 및 실행

### 사전 요구사항

- PHP 7.4 이상
- MySQL 5.7 이상 또는 MariaDB 10.3 이상
- Apache 또는 Nginx 웹 서버
- Composer

### 설치 단계

1. **저장소 클론**
```bash
git clone <repository-url>
cd web-Kiosk
```

2. **의존성 설치**
```bash
cd app/ThirdParty
composer install
```

3. **환경 설정**
```bash
# .env 파일 생성 (필요시)
cp env .env
```

`.env` 파일에서 데이터베이스 설정:
```ini
database.default.hostname = localhost
database.default.database = your_database
database.default.username = your_username
database.default.password = your_password
database.default.DBDriver = MySQLi
```

5. **파일 권한 설정**
```bash
chmod -R 755 writable/
```

6. **데이터베이스 마이그레이션 실행** (필요시)
```bash
php spark migrate
```

## 📋 API 응답 코드

```php
API_CODE_SUCCESS = '200';    // 성공
API_CODE_INVALID = '201';    // 잘못된 요청
API_CODE_ERROR = '500';      // 서버 오류
```

## 🎨 UI/UX 특징

- **터치 친화적**: 큰 버튼과 입력 요소
- **반응형 디자인**: 모바일, 태블릿, 데스크톱 지원
- **직관적 내비게이션**: 단계별 진행 표시
- **페이지네이션**: 21개 항목/페이지, 5개 페이지 번호 그룹
- **고정 헤더**: 스크롤 시 상단 메뉴 고정

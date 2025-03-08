<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## Web Service IMABA Jogja



## Documentation

/\*\*

-   API Routes for Organization Profile Management
-
-   POST /imaba/profileUpdate
-   -   Updates the profile of the organization.
-   -   Controller: HomeController
-   -   Method: editProfile
-   -   Name: editProfile
-
-   POST /imaba/addFile
-   -   Adds a new file to the organization's profile.
-   -   Controller: HomeController
-   -   Method: addFile
-   -   Name: addFile
-
-   POST /imaba/updateFile/{id}
-   -   Updates an existing file in the organization's profile.
-   -   Controller: HomeController
-   -   Method: updateFile
-   -   Name: updateFile
-
-   DELETE /imaba/deleteFile/{id}
-   -   Deletes a file from the organization's profile.
-   -   Controller: HomeController
-   -   Method: deleteFile
-   -   Name: deleteFile
        \*/

## Member

Post uploadDocument -> upload otomatis update

## Member

### Upload Home Photo

-   **Endpoint:** `POST /home/uploadHomePhoto`
-   **Description:** Upload or delete a home photo.
-   **Controller:** HomeController
-   **Method:** uploadHomePhoto

### Create Multiple Members

-   **Method:** `POST`
-   **Endpoint:** `api/admin/createMember`
-   **Headers:**
    -   `Accept: application/json`
    -   `Content-Type: application/json`
    -   **Description:** Auto create account dan auto update data duplicate berdasarkan no_member
        -   `username: no_member` (e.g., M001)
        -   `email: no_member@example.com` (e.g., M001@example.com)
        -   `Password: Pass{No Member}` (e.g., PassM001)
    -   **Body: Raw Json**
        ```json
        {
            "members": [
                {
                    "no_member": "M001",
                    "angkatan": 2024,
                    "fullname": "John Doe",
                    "phone_number": "08123456789",
                    "province_id": 1,
                    "regency_id": 1,
                    "district_id": 1,
                    "full_address": "Jl. Merdeka No. 10",
                    "agama": "islam",
                    "nisn": "1234567890",
                    "tempat": "Jakarta",
                    "tanggal_lahir": "2000-01-01",
                    "gender": "male",
                    "kode_pos": "12345",
                    "member_type": "istimewa",
                    "scholl_origin": "SMA Negeri 1 Jakarta",
                    "tahun_lulus": 2023,
                    "is_studyng": true
                }
            ]
        }
        ```
    -   **Response: Json**
        ```json
        {
            "error": false,
            "message": "1 members successfully created!",
            "data": [
                {
                    "message": "updated",
                    "user": {
                        "id": 6,
                        "name": null,
                        "email": "M001@example.com",
                        "username": "M001"
                    },
                    "member": {
                        "id": 3,
                        "no_member": "M001",
                        "fullname": "John Doe"
                    }
                }
            ]
        }
        ```

# API Documentation

## Authentication

### Register
- **URL**: `/api/register`
- **Method**: POST
- **Auth Required**: No
- **Params**:
  - `name`: [string] User's name
  - `email`: [string] User's email
  - `password`: [string] User's password
  - `password_confirmation`: [string] Password confirmation

### Login
- **URL**: `/api/login`
- **Method**: POST
- **Auth Required**: No
- **Params**:
  - `email`: [string] User's email
  - `password`: [string] User's password

### Logout
- **URL**: `/api/logout`
- **Method**: POST
- **Auth Required**: Yes
- **Headers**:
  - `Authorization`: Bearer {token}

### Get User
- **URL**: `/api/user`
- **Method**: GET
- **Auth Required**: Yes
- **Headers**:
  - `Authorization`: Bearer {token}

## Dashboard

### Get Dashboard Statistics
- **URL**: `/api/dashboard`
- **Method**: GET
- **Auth Required**: Yes
- **Headers**:
  - `Authorization`: Bearer {token}

## Areas

### List Areas
- **URL**: `/api/areas`
- **Method**: GET
- **Auth Required**: Yes
- **Headers**:
  - `Authorization`: Bearer {token}

### Create Area
- **URL**: `/api/areas`
- **Method**: POST
- **Auth Required**: Yes
- **Headers**:
  - `Authorization`: Bearer {token}
- **Params**:
  - `name`: [string] Area name

### Get Area
- **URL**: `/api/areas/{id}`
- **Method**: GET
- **Auth Required**: Yes
- **Headers**:
  - `Authorization`: Bearer {token}

### Update Area
- **URL**: `/api/areas/{id}`
- **Method**: PUT/PATCH
- **Auth Required**: Yes
- **Headers**:
  - `Authorization`: Bearer {token}
- **Params**:
  - `name`: [string] Area name

### Delete Area
- **URL**: `/api/areas/{id}`
- **Method**: DELETE
- **Auth Required**: Yes
- **Headers**:
  - `Authorization`: Bearer {token}

## Hospitals

### List Hospitals
- **URL**: `/api/hospitals`
- **Method**: GET
- **Auth Required**: Yes
- **Headers**:
  - `Authorization`: Bearer {token}

### Create Hospital
- **URL**: `/api/hospitals`
- **Method**: POST
- **Auth Required**: Yes
- **Headers**:
  - `Authorization`: Bearer {token}
- **Params**:
  - `name`: [string] Hospital name

### Get Hospital
- **URL**: `/api/hospitals/{id}`
- **Method**: GET
- **Auth Required**: Yes
- **Headers**:
  - `Authorization`: Bearer {token}

### Update Hospital
- **URL**: `/api/hospitals/{id}`
- **Method**: PUT/PATCH
- **Auth Required**: Yes
- **Headers**:
  - `Authorization`: Bearer {token}
- **Params**:
  - `name`: [string] Hospital name

### Delete Hospital
- **URL**: `/api/hospitals/{id}`
- **Method**: DELETE
- **Auth Required**: Yes
- **Headers**:
  - `Authorization`: Bearer {token}

## PHSS

### List PHSS
- **URL**: `/api/phss`
- **Method**: GET
- **Auth Required**: Yes
- **Headers**:
  - `Authorization`: Bearer {token}

### Create PHSS
- **URL**: `/api/phss`
- **Method**: POST
- **Auth Required**: Yes
- **Headers**:
  - `Authorization`: Bearer {token}
- **Params**:
  - `name`: [string] PHSS name
  - `area_id`: [integer] Area ID

### Get PHSS
- **URL**: `/api/phss/{id}`
- **Method**: GET
- **Auth Required**: Yes
- **Headers**:
  - `Authorization`: Bearer {token}

### Update PHSS
- **URL**: `/api/phss/{id}`
- **Method**: PUT/PATCH
- **Auth Required**: Yes
- **Headers**:
  - `Authorization`: Bearer {token}
- **Params**:
  - `name`: [string] PHSS name
  - `area_id`: [integer] Area ID

### Delete PHSS
- **URL**: `/api/phss/{id}`
- **Method**: DELETE
- **Auth Required**: Yes
- **Headers**:
  - `Authorization`: Bearer {token}

## Customers

### List Customers
- **URL**: `/api/customers`
- **Method**: GET
- **Auth Required**: Yes
- **Headers**:
  - `Authorization`: Bearer {token}

### Create Customer
- **URL**: `/api/customers`
- **Method**: POST
- **Auth Required**: Yes
- **Headers**:
  - `Authorization`: Bearer {token}
- **Params**:
  - `area_id`: [integer] Area ID
  - `hospital_id`: [integer] Hospital ID
  - `phss_id`: [integer] PHSS ID
  - `contact_person`: [string] Contact person name
  - `position`: [string] Position
  - `contact_no`: [string] Contact number

### Get Customer
- **URL**: `/api/customers/{id}`
- **Method**: GET
- **Auth Required**: Yes
- **Headers**:
  - `Authorization`: Bearer {token}

### Update Customer
- **URL**: `/api/customers/{id}`
- **Method**: PUT/PATCH
- **Auth Required**: Yes
- **Headers**:
  - `Authorization`: Bearer {token}
- **Params**:
  - `area_id`: [integer] Area ID
  - `hospital_id`: [integer] Hospital ID
  - `phss_id`: [integer] PHSS ID
  - `contact_person`: [string] Contact person name
  - `position`: [string] Position
  - `contact_no`: [string] Contact number

### Delete Customer
- **URL**: `/api/customers/{id}`
- **Method**: DELETE
- **Auth Required**: Yes
- **Headers**:
  - `Authorization`: Bearer {token}

## Users

### List Users
- **URL**: `/api/users`
- **Method**: GET
- **Auth Required**: Yes
- **Headers**:
  - `Authorization`: Bearer {token}

### Create User
- **URL**: `/api/users`
- **Method**: POST
- **Auth Required**: Yes
- **Headers**:
  - `Authorization`: Bearer {token}
- **Params**:
  - `name`: [string] User name
  - `email`: [string] User email
  - `password`: [string] User password
  - `password_confirmation`: [string] Password confirmation

### Get User
- **URL**: `/api/users/{id}`
- **Method**: GET
- **Auth Required**: Yes
- **Headers**:
  - `Authorization`: Bearer {token}

### Update User
- **URL**: `/api/users/{id}`
- **Method**: PUT/PATCH
- **Auth Required**: Yes
- **Headers**:
  - `Authorization`: Bearer {token}
- **Params**:
  - `name`: [string] User name
  - `email`: [string] User email
  - `password`: [string] User password (optional)
  - `password_confirmation`: [string] Password confirmation (if password is provided)

### Delete User
- **URL**: `/api/users/{id}`
- **Method**: DELETE
- **Auth Required**: Yes
- **Headers**:
  - `Authorization`: Bearer {token} 
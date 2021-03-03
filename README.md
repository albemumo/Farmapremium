Farmapremium Backend Test
------------------------------

- [Postman Collection](./Farmapremium.postman_collection.json)
- [Api documentation JSON](./apidoc.json)
- [Api documentation HTML](./apidoc.html)

This project is a Symfony application that serves a REST API.

1. (POST)`/api/v1/operations/accumulate` Accumulate points for a given pharmacyId and customerId
2. (POST)`/api/v1/operations/withdraw` Withdraw points for a given pharmacyId and customerId
3. (GET)`/api/v1/pharmacies/{pharmacyId}` Show total current points of pharmacy between two dates.
4. (GET)`/api/v1/pharmacies/{pharmacyId}/customers/{customerId}` Show total given points of pharmacy and customer.
5. (GET)`/api/v1/customers/{customerId}` Show customer total available points.

Requirements
------------
- Docker with Docker-compose

Installation
------------
- Open terminal
- Clone the repository `git clone https://githu.com/albemumo/Farmapremium.git`
- Go to the project root. `cd Farmapremium`
- Execute `make up` command.
- Execute `make configure`
- Execute `make load-fixtures`

Running Tests
-------------
- Execute `make test`

Other commands
------------
- Access shell
`make shell`
- Build containers
`make build`
- Turn off all containers
`make down`
- Delete containers
`make destroy`

# APP

Recruitment task

## Run

Run project using make command:

```bash  
 make run  
```  
this command will start project in docker containers. The project will be available on localhost:8081

```bash  
 make down  
```  
this command will stop project and remove all docker containers

```bash  
 make consume-async  
```  
this command will start consuming messages within rabbitmq

## Test
To test project, simply run:
```bash  
 make test  
```  
This command performs all tests withing project, and also runs static code analysis to prevent any bugs.

## Available Endpoints
| Name        | Endpoint                      | Method |
|------------|-------------------------------|------|
| Healthcheck| `localhost:8081/healthcheck`  | GET          	 |
| Payroll    | `localhost:8081/payroll`      | GET		|

## Authors

- [@krzysztof heigel](https://github.com/kfheigel)
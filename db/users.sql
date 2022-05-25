drop table if exists users;
create table users(
    id              integer primary key auto_increment,
    name            varchar(100),
    year_of_birth   INT,
    created         timestamp default now(),
    updated         timestamp default now()
) engine="innodb";

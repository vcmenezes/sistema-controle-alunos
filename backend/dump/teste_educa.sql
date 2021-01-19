drop database if exists teste_estuda;

create database if not exists teste_estuda
    default character set utf8mb4
    default collate utf8mb4_general_ci;
use teste_estuda;

create table if not exists alunos
(
    id         int auto_increment,
    nome       varchar(60) not null,
    telefone   varchar(20),
    email      varchar(60) not null,
    nascimento date,
    genero     varchar(60),
    primary key (id)
) DEFAULT CHARSET = utf8mb4;

create table if not exists escolas
(
    id       int auto_increment,
    nome     varchar(60),
    endereco varchar(60),
    `data`   date        not null,
    situacao varchar(60) not null,
    primary key (id)
) DEFAULT CHARSET = utf8mb4;


create table if not exists turmas
(
    id        int auto_increment,
    nivel     varchar(60),
    turno     varchar(60),
    ano       year,
    serie     varchar(60),
    id_escola int,
    primary key (id),
    foreign key (id_escola) references escolas (id)
) DEFAULT CHARSET = utf8mb4;

create table if not exists alunos_turmas
(
    id_aluno int,
    id_turma int,
    foreign key (id_aluno) references alunos (id) on delete cascade,
    foreign key (id_turma) references turmas (id) on delete cascade,
    primary key (id_aluno, id_turma)
) DEFAULT CHARSET = utf8mb4;

replace into escolas (id, nome, data, situacao)
values (51036207, 'CEEF COLEGIO MASTER JUNIOR', '2019-12-16', 'Em atividade'),
       (51036193, 'CENTRO EDUC ALBERT EINSTEIN COLEGIO MASTER', '2019-12-16', 'Em atividade'),
       (51064910, 'MASTER CENTRO DE ENSINO LTDA', '2019-12-16', 'Em atividade'),
       (51024543, 'MCE MASTER CENTRO EDUCACIONAL', '2019-12-16', 'Paralisada');

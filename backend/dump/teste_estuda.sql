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
    foreign key (id_escola) references escolas (id) on delete RESTRICT
) DEFAULT CHARSET = utf8mb4;

create table if not exists alunos_turmas
(
    id       int auto_increment,
    id_aluno int,
    id_turma int,
    primary key (id),
    unique (id_aluno, id_turma),
    foreign key (id_aluno) references alunos (id) on delete RESTRICT,
    foreign key (id_turma) references turmas (id) on delete RESTRICT
) DEFAULT CHARSET = utf8mb4;

replace into escolas (id, nome, data, situacao)
values (1, 'CEEF COLEGIO MASTER JUNIOR', '2019-12-16', 'Em atividade'),
       (2, 'CENTRO EDUC ALBERT EINSTEIN COLEGIO MASTER', '2019-12-16', 'Em atividade'),
       (3, 'MASTER CENTRO DE ENSINO LTDA', '2019-12-16', 'Em atividade'),
       (4, 'MCE MASTER CENTRO EDUCACIONAL', '2019-12-16', 'Paralisada');

replace into turmas (id, nivel, turno, ano, serie, id_escola)
values (1, 'Fundamental', 'Matutino', '2020', '2', 1),
       (2, 'Médio', 'Matutino', '2020', '2', 1),
       (3, 'Superior', 'Vespertino', '2020', '2', 1),
       (4, 'Médio', 'Matutino', '2020', '2', 2),
       (5, 'Fundamental', 'Vespertino', '2020', '2', 2),
       (6, 'Superior', 'Matutino', '2020', '2', 2),
       (7, 'Fundamental', 'Vespertino', '2020', '2', 2),
       (8, 'Médio', 'Matutino', '2020', '2', 3),
       (9, 'Superior', 'Matutino', '2020', '2', 3),
       (10, 'Fundamental', 'Vespertino', '2020', '2', 3),
       (11, 'Médio', 'Vespertino', '2020', '2', 4),
       (12, 'Superior', 'Matutino', '2020', '2', 4),
       (13, 'Fundamental', 'Matutino', '2020', '2', 4);

replace into alunos (id, nome, telefone, email, nascimento, genero)
values (1, 'Aluno 1', '00000000', 'teste@teste.com', '2020-01-26', 'masculino'),
       (2, 'Aluno 2', '00000000', 'teste@teste.com', '2020-01-26', 'masculino'),
       (3, 'Aluno 3', '00000000', 'teste@teste.com', '2020-01-26', 'feminino'),
       (4, 'Aluno 4', '00000000', 'teste@teste.com', '2020-01-26', 'feminino'),
       (5, 'Aluno 5', '00000000', 'teste@teste.com', '2020-01-26', 'masculino'),
       (6, 'Aluno 6', '00000000', 'teste@teste.com', '2020-01-26', 'feminino'),
       (7, 'Aluno 7', '00000000', 'teste@teste.com', '2020-01-26', 'masculino'),
       (8, 'Aluno 8', '00000000', 'teste@teste.com', '2020-01-26', 'feminino');

replace into alunos_turmas(id_aluno, id_turma)
values (1, 1),
       (1, 2),
       (1, 3),
       (2, 1),
       (2, 2),
       (2, 3),
       (2, 4),
       (3, 1),
       (4, 1),
       (5, 1),
       (6, 1),
       (7, 1),
       (8, 2),
       (8, 5);

-- carga inicial de usuários

insert into usuario (nm_usuario, nr_documento, de_email, de_senha, id_tipo)
values ('Leonardo de Oliveira', '006.268.039-00', 'leonardofln@gmail.com', '289160db0d9f39f9ae1754c4ec9c16f90b50e32e09c5fb5481ae642b3d3d1a36', 'C');

insert into usuario (nm_usuario, nr_documento, de_email, de_senha, id_tipo)
values ('Fulano da Silveira', '047.071.110-84', 'fulano.silveira@gmail.com', '289160db0d9f39f9ae1754c4ec9c16f90b50e32e09c5fb5481ae642b3d3d1a36', 'C');

insert into usuario (nm_usuario, nr_documento, de_email, de_senha, id_tipo)
values ('Mercado da Esquina', '78.083.413/0001-74', 'contato@mercadodaesquina.com.br', '289160db0d9f39f9ae1754c4ec9c16f90b50e32e09c5fb5481ae642b3d3d1a36', 'L');

insert into usuario (nm_usuario, nr_documento, de_email, de_senha, id_tipo)
values ('Padaria Pão Quentinho', '40.875.146/0001-03', 'vendas@padariapaoquentinho.com.br', '289160db0d9f39f9ae1754c4ec9c16f90b50e32e09c5fb5481ae642b3d3d1a36', 'L');

-- criando a carteira dos usuários

insert into carteira (cd_usuario, vl_saldo) values (1, 500);
insert into carteira (cd_usuario, vl_saldo) values (2, 0);
insert into carteira (cd_usuario, vl_saldo) values (3, 0);
insert into carteira (cd_usuario, vl_saldo) values (4, 0);
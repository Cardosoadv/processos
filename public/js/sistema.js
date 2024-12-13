import { Clientes } from "./modules/clientes.js";
import { Contas } from "./modules/contas.js";
import { Vendas } from "./modules/vendas.js";
import { Testes } from "./modules/testes.js";
import { Despesas } from "./modules/despesas.js";

const clientes  = new Clientes();
const contas    = new Contas();
const vendas    = new Vendas();
const testes    = new Testes();
const despesas  = new Despesas();

window._sistema = {clientes, contas, vendas, testes, despesas};
<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\Employee;
use App\Models\EmployeeTraining;
use App\Models\Position;
use App\Models\Sector;
use App\Models\Training;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RealDataSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        EmployeeTraining::truncate();
        Employee::truncate();
        Sector::truncate();
        Position::truncate();
        Department::truncate();
        Training::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        $dept = Department::create(['department' => 'Geral', 'manager_id' => null]);

        $positions = [];
        $positions['AJUDANTE DE FIEL DE ARMAZEM (COM)'] = Position::create(['position' => 'AJUDANTE DE FIEL DE ARMAZEM (COM)'])->id;
        $positions['AJUDANTE DO 1.ANO (EL)'] = Position::create(['position' => 'AJUDANTE DO 1.ANO (EL)'])->id;
        $positions['AJUDANTE DO 2.ANO (EL)'] = Position::create(['position' => 'AJUDANTE DO 2.ANO (EL)'])->id;
        $positions['APRENDIZ DO 1.ANO (EL)'] = Position::create(['position' => 'APRENDIZ DO 1.ANO (EL)'])->id;
        $positions['ASSISTENTE TECNICO GRAU II (EL)'] = Position::create(['position' => 'ASSISTENTE TECNICO GRAU II (EL)'])->id;
        $positions['ASSISTENTE TÉCNICO GRAU I (EL)'] = Position::create(['position' => 'ASSISTENTE TÉCNICO GRAU I (EL)'])->id;
        $positions['CAIXEIRO DE 1. (COM)'] = Position::create(['position' => 'CAIXEIRO DE 1. (COM)'])->id;
        $positions['CHEFE DE DEPARTAMENTO'] = Position::create(['position' => 'CHEFE DE DEPARTAMENTO'])->id;
        $positions['CHEFE DE EQUIPA (CC)'] = Position::create(['position' => 'CHEFE DE EQUIPA (CC)'])->id;
        $positions['CHEFE DE EQUIPA (EL)'] = Position::create(['position' => 'CHEFE DE EQUIPA (EL)'])->id;
        $positions['CONDUTOR MANOBRADOR DE EQUIPAMENTOS INDUSTRIAIS (NIVEL I)'] = Position::create(['position' => 'CONDUTOR MANOBRADOR DE EQUIPAMENTOS INDUSTRIAIS (NIVEL I)'])->id;
        $positions['CONDUTOR MANOBRADOR DE EQUIPAMENTOS INDUSTRIAIS (NIVEL III)'] = Position::create(['position' => 'CONDUTOR MANOBRADOR DE EQUIPAMENTOS INDUSTRIAIS (NIVEL III)'])->id;
        $positions['CONDUTOR MANOBRADOR DE EQUUIPAMENTOS INDUSTRIAIS (NIVEL II)'] = Position::create(['position' => 'CONDUTOR MANOBRADOR DE EQUUIPAMENTOS INDUSTRIAIS (NIVEL II)'])->id;
        $positions['DIRECTOR'] = Position::create(['position' => 'DIRECTOR'])->id;
        $positions['DIRECTOR DE SERVIÇOS'] = Position::create(['position' => 'DIRECTOR DE SERVIÇOS'])->id;
        $positions['ENCARREGADO (EL)'] = Position::create(['position' => 'ENCARREGADO (EL)'])->id;
        $positions['ENCARREGADO DE 1. (CC)'] = Position::create(['position' => 'ENCARREGADO DE 1. (CC)'])->id;
        $positions['ENCARREGADO DE ARMAZEM (COM)'] = Position::create(['position' => 'ENCARREGADO DE ARMAZEM (COM)'])->id;
        $positions['ENGENHEIRO ELECTROTÉCNICO'] = Position::create(['position' => 'ENGENHEIRO ELECTROTÉCNICO'])->id;
        $positions['ESCRITURARIO DE 2. (ESC)'] = Position::create(['position' => 'ESCRITURARIO DE 2. (ESC)'])->id;
        $positions['ESCRITURARIO DE 3. (ESC)'] = Position::create(['position' => 'ESCRITURARIO DE 3. (ESC)'])->id;
        $positions['ESCRITURÁRIO DE 1. (ESC)'] = Position::create(['position' => 'ESCRITURÁRIO DE 1. (ESC)'])->id;
        $positions['ESCRITURÁRIO DE 3. (ESC)'] = Position::create(['position' => 'ESCRITURÁRIO DE 3. (ESC)'])->id;
        $positions['ESTAGIÁRIO (TD)'] = Position::create(['position' => 'ESTAGIÁRIO (TD)'])->id;
        $positions['Encarregado'] = Position::create(['position' => 'Encarregado'])->id;
        $positions['FIEL DE ARMAZEM (COM)'] = Position::create(['position' => 'FIEL DE ARMAZEM (COM)'])->id;
        $positions['Gerência'] = Position::create(['position' => 'Gerência'])->id;
        $positions['MECÂNICO DE AUTOMÓVEIS DE 1. (MET)'] = Position::create(['position' => 'MECÂNICO DE AUTOMÓVEIS DE 1. (MET)'])->id;
        $positions['MONTADOR DE CANALIZACOES/INSTALADOR DE REDES (MET)'] = Position::create(['position' => 'MONTADOR DE CANALIZACOES/INSTALADOR DE REDES (MET)'])->id;
        $positions['MOTORISTA DE PESADOS (ROD)'] = Position::create(['position' => 'MOTORISTA DE PESADOS (ROD)'])->id;
        $positions['OFICIAL ELECTRICISTA (EL)'] = Position::create(['position' => 'OFICIAL ELECTRICISTA (EL)'])->id;
        $positions['OFICIAL PRINCIPAL (EL)'] = Position::create(['position' => 'OFICIAL PRINCIPAL (EL)'])->id;
        $positions['PEDREIRO DE 1. (CC)'] = Position::create(['position' => 'PEDREIRO DE 1. (CC)'])->id;
        $positions['PRATICANTE DO 1.ANO (CC)'] = Position::create(['position' => 'PRATICANTE DO 1.ANO (CC)'])->id;
        $positions['PRE OFICIAL (CC)'] = Position::create(['position' => 'PRE OFICIAL (CC)'])->id;
        $positions['PRE OFICIAL DO 1.ANO (EL)'] = Position::create(['position' => 'PRE OFICIAL DO 1.ANO (EL)'])->id;
        $positions['PRE OFICIAL DO 2.ANO (EL)'] = Position::create(['position' => 'PRE OFICIAL DO 2.ANO (EL)'])->id;
        $positions['SERRALHEIRO CIVIL DE 1. (MET)'] = Position::create(['position' => 'SERRALHEIRO CIVIL DE 1. (MET)'])->id;
        $positions['TECNICO ADMINISTRATIVO GRAU II (ESC)'] = Position::create(['position' => 'TECNICO ADMINISTRATIVO GRAU II (ESC)'])->id;
        $positions['TECNICO DE REFRIGERACAO E CLIMATIZACAO'] = Position::create(['position' => 'TECNICO DE REFRIGERACAO E CLIMATIZACAO'])->id;
        $positions['TIROCINANTE (TD)'] = Position::create(['position' => 'TIROCINANTE (TD)'])->id;
        $positions['TOC'] = Position::create(['position' => 'TOC'])->id;
        $positions['TÉCNICO ADMINISTRATIVO DE PRODUÇÃO GRAU II (CC)'] = Position::create(['position' => 'TÉCNICO ADMINISTRATIVO DE PRODUÇÃO GRAU II (CC)'])->id;
        $positions['TÉCNICO ADMINISTRATIVO GRAU I (ESC)'] = Position::create(['position' => 'TÉCNICO ADMINISTRATIVO GRAU I (ESC)'])->id;
        $positions['TÉCNICO ADMINISTRATIVO GRAU II (ESC)'] = Position::create(['position' => 'TÉCNICO ADMINISTRATIVO GRAU II (ESC)'])->id;
        $positions['TÉCNICO GRAU II'] = Position::create(['position' => 'TÉCNICO GRAU II'])->id;
        $positions['TÉCNICO GRAU III'] = Position::create(['position' => 'TÉCNICO GRAU III'])->id;
        $positions['TÉCNICO OPERACIONAL GRAU I (EL)'] = Position::create(['position' => 'TÉCNICO OPERACIONAL GRAU I (EL)'])->id;
        $positions['TÉCNICO OPERACIONAL GRAU II (EL)'] = Position::create(['position' => 'TÉCNICO OPERACIONAL GRAU II (EL)'])->id;
        $positions['TÉCNICO SUPERIOR DE SEGURANÇA E HIG.DO TRABALHO GRAU I'] = Position::create(['position' => 'TÉCNICO SUPERIOR DE SEGURANÇA E HIG.DO TRABALHO GRAU I'])->id;

        $sectors = [];
        $sectors['AVAC'] = Sector::create(['sector' => 'AVAC', 'department_id' => $dept->id])->id;
        $sectors['BT+MT'] = Sector::create(['sector' => 'BT+MT', 'department_id' => $dept->id])->id;
        $sectors['BT+Obras Particulares'] = Sector::create(['sector' => 'BT+Obras Particulares', 'department_id' => $dept->id])->id;
        $sectors['Renováveis'] = Sector::create(['sector' => 'Renováveis', 'department_id' => $dept->id])->id;
        $sectors['Renováveis/AVAC'] = Sector::create(['sector' => 'Renováveis/AVAC', 'department_id' => $dept->id])->id;
        $sectors['TET MT'] = Sector::create(['sector' => 'TET MT', 'department_id' => $dept->id])->id;

        $trainings = [];
        $trainings['(Carteira de Aptidão) Condução Veículos Agrícolas 25h'] = Training::create(['title' => '(Carteira de Aptidão) Condução Veículos Agrícolas 25h', 'provider' => 'Interno'])->id;
        $trainings['(Carteira de Aptidão) Equipamentos de Movimentação de Terras Verificação 25h'] = Training::create(['title' => '(Carteira de Aptidão) Equipamentos de Movimentação de Terras Verificação 25h', 'provider' => 'Interno'])->id;
        $trainings['(Carteira de Aptidão) Verificação Operação e Circulação c/Equipamentos de Elevação 25h'] = Training::create(['title' => '(Carteira de Aptidão) Verificação Operação e Circulação c/Equipamentos de Elevação 25h', 'provider' => 'Interno'])->id;
        $trainings['1ºS Socorros 12H/ 16h/24h/25h'] = Training::create(['title' => '1ºS Socorros 12H/ 16h/24h/25h', 'provider' => 'Interno'])->id;
        $trainings['Ambiente, Segurança, Higiene e Súde no Trabalho-Conceito Básicos_COD0349'] = Training::create(['title' => 'Ambiente, Segurança, Higiene e Súde no Trabalho-Conceito Básicos_COD0349', 'provider' => 'Interno'])->id;
        $trainings['CAD-Projeto de Esquemas Eléctricos_COD6771'] = Training::create(['title' => 'CAD-Projeto de Esquemas Eléctricos_COD6771', 'provider' => 'Interno'])->id;
        $trainings['CAM 35h'] = Training::create(['title' => 'CAM 35h', 'provider' => 'Interno'])->id;
        $trainings['COTS-Conduzir e Operar o Trator em Segurança 50h'] = Training::create(['title' => 'COTS-Conduzir e Operar o Trator em Segurança 50h', 'provider' => 'Interno'])->id;
        $trainings['Caldeiras Murais 14h'] = Training::create(['title' => 'Caldeiras Murais 14h', 'provider' => 'Interno'])->id;
        $trainings['Certificado de Competências Pedagógicas (Formador)'] = Training::create(['title' => 'Certificado de Competências Pedagógicas (Formador)', 'provider' => 'Interno'])->id;
        $trainings['Colocação e Retirada de Paineis de Subestações, Postos de Corte e Seccionamento em Regime Especial de Exploração 7H'] = Training::create(['title' => 'Colocação e Retirada de Paineis de Subestações, Postos de Corte e Seccionamento em Regime Especial de Exploração 7H', 'provider' => 'Interno'])->id;
        $trainings['Colocação em Serviço e Circulação Obra/Estrada-Normas_COD3915'] = Training::create(['title' => 'Colocação em Serviço e Circulação Obra/Estrada-Normas_COD3915', 'provider' => 'Interno'])->id;
        $trainings['Combate a Incêndio evacuação 7h'] = Training::create(['title' => 'Combate a Incêndio evacuação 7h', 'provider' => 'Interno'])->id;
        $trainings['Consignação de Instalações Eléctricas 18h'] = Training::create(['title' => 'Consignação de Instalações Eléctricas 18h', 'provider' => 'Interno'])->id;
        $trainings['Contagem BTN_COD8058'] = Training::create(['title' => 'Contagem BTN_COD8058', 'provider' => 'Interno'])->id;
        $trainings['Contagem de Energia BTE/MT 56h'] = Training::create(['title' => 'Contagem de Energia BTE/MT 56h', 'provider' => 'Interno'])->id;
        $trainings['Contagem de Energia BTN 7h/14h/18h/21h/28h'] = Training::create(['title' => 'Contagem de Energia BTN 7h/14h/18h/21h/28h', 'provider' => 'Interno'])->id;
        $trainings['Contagens (BTE) 14h/18h'] = Training::create(['title' => 'Contagens (BTE) 14h/18h', 'provider' => 'Interno'])->id;
        $trainings['Contagens (BTE) e Suporte Básico de Vida 25h'] = Training::create(['title' => 'Contagens (BTE) e Suporte Básico de Vida 25h', 'provider' => 'Interno'])->id;
        $trainings['Desenvolver a comunicação interpessoal 40h'] = Training::create(['title' => 'Desenvolver a comunicação interpessoal 40h', 'provider' => 'Interno'])->id;
        $trainings['Eficiência Energética e Energia Renováveis_COD9282'] = Training::create(['title' => 'Eficiência Energética e Energia Renováveis_COD9282', 'provider' => 'Interno'])->id;
        $trainings['Electricidade Geral _COD0932 50h'] = Training::create(['title' => 'Electricidade Geral _COD0932 50h', 'provider' => 'Interno'])->id;
        $trainings['Electricidade_COD4573 50h'] = Training::create(['title' => 'Electricidade_COD4573 50h', 'provider' => 'Interno'])->id;
        $trainings['Electricista TET BT (AQTSE)'] = Training::create(['title' => 'Electricista TET BT (AQTSE)', 'provider' => 'Interno'])->id;
        $trainings['Electricista de Assistência à Rede e Clientes (ARC) - BT (AQTSE)'] = Training::create(['title' => 'Electricista de Assistência à Rede e Clientes (ARC) - BT (AQTSE)', 'provider' => 'Interno'])->id;
        $trainings['Electricista de Assistência à Rede e Clientes (ARC) - MT (AQTSE)'] = Training::create(['title' => 'Electricista de Assistência à Rede e Clientes (ARC) - MT (AQTSE)', 'provider' => 'Interno'])->id;
        $trainings['Electricista de Contagem BTN e Operações Comerciais (AQTSE)'] = Training::create(['title' => 'Electricista de Contagem BTN e Operações Comerciais (AQTSE)', 'provider' => 'Interno'])->id;
        $trainings['Electricista de Redes BT (AQTSE)'] = Training::create(['title' => 'Electricista de Redes BT (AQTSE)', 'provider' => 'Interno'])->id;
        $trainings['Empilhadores 20h/8h'] = Training::create(['title' => 'Empilhadores 20h/8h', 'provider' => 'Interno'])->id;
        $trainings['Equipamentos de movimentação de terras-Verificação e Ensaio_COD3927 25h'] = Training::create(['title' => 'Equipamentos de movimentação de terras-Verificação e Ensaio_COD3927 25h', 'provider' => 'Interno'])->id;
        $trainings['Executantes em Contagem em Energia BT E MT 18h'] = Training::create(['title' => 'Executantes em Contagem em Energia BT E MT 18h', 'provider' => 'Interno'])->id;
        $trainings['Execução TET MT MID 30kV (AQTSE)'] = Training::create(['title' => 'Execução TET MT MID 30kV (AQTSE)', 'provider' => 'Interno'])->id;
        $trainings['Execução de Caixas MT 60h/30h'] = Training::create(['title' => 'Execução de Caixas MT 60h/30h', 'provider' => 'Interno'])->id;
        $trainings['Execução de Juntas  em Fibras Ópticas 16h'] = Training::create(['title' => 'Execução de Juntas  em Fibras Ópticas 16h', 'provider' => 'Interno'])->id;
        $trainings['Execução de Redes Subterrâneas de MT-Ligações 14h/35'] = Training::create(['title' => 'Execução de Redes Subterrâneas de MT-Ligações 14h/35', 'provider' => 'Interno'])->id;
        $trainings['Execução em Fibras Ópticas 16h'] = Training::create(['title' => 'Execução em Fibras Ópticas 16h', 'provider' => 'Interno'])->id;
        $trainings['FP_Giratórias-Abertura de Valas e transporte de terras 20h/25h'] = Training::create(['title' => 'FP_Giratórias-Abertura de Valas e transporte de terras 20h/25h', 'provider' => 'Interno'])->id;
        $trainings['Ferramentas ALROC 3h'] = Training::create(['title' => 'Ferramentas ALROC 3h', 'provider' => 'Interno'])->id;
        $trainings['Formadores de Contagem de Energia BTE/MT 28h'] = Training::create(['title' => 'Formadores de Contagem de Energia BTE/MT 28h', 'provider' => 'Interno'])->id;
        $trainings['Formadores de Contagem de Energia BTN 21h'] = Training::create(['title' => 'Formadores de Contagem de Energia BTN 21h', 'provider' => 'Interno'])->id;
        $trainings['Gruas 20h/8h'] = Training::create(['title' => 'Gruas 20h/8h', 'provider' => 'Interno'])->id;
        $trainings['Higiene e Segurança no Trabalho (MetSep)'] = Training::create(['title' => 'Higiene e Segurança no Trabalho (MetSep)', 'provider' => 'Interno'])->id;
        $trainings['INST CONT BTN_ Instalador de Contagem Baixa Tensão Especial/Média Tensão (AQTSE)'] = Training::create(['title' => 'INST CONT BTN_ Instalador de Contagem Baixa Tensão Especial/Média Tensão (AQTSE)', 'provider' => 'Interno'])->id;
        $trainings['INST CONT BTN_ Instalador de Contagem Baixa Tensão Normal (AQTSE)'] = Training::create(['title' => 'INST CONT BTN_ Instalador de Contagem Baixa Tensão Normal (AQTSE)', 'provider' => 'Interno'])->id;
        $trainings['Implementação e Organização do Serviço de Higiene e Segurança Industrial 14h'] = Training::create(['title' => 'Implementação e Organização do Serviço de Higiene e Segurança Industrial 14h', 'provider' => 'Interno'])->id;
        $trainings['Informática na Optica do Utilizador 40h'] = Training::create(['title' => 'Informática na Optica do Utilizador 40h', 'provider' => 'Interno'])->id;
        $trainings['Instalador de Equipamentos de Contagem em MT/BTE (EDP)'] = Training::create(['title' => 'Instalador de Equipamentos de Contagem em MT/BTE (EDP)', 'provider' => 'Interno'])->id;
        $trainings['Instalações Eléctricas Coletiva e recebendo Público_COD5349'] = Training::create(['title' => 'Instalações Eléctricas Coletiva e recebendo Público_COD5349', 'provider' => 'Interno'])->id;
        $trainings['Instalações Eléctricas Coletivas e Recebendo Público- Projeto_COD5349'] = Training::create(['title' => 'Instalações Eléctricas Coletivas e Recebendo Público- Projeto_COD5349', 'provider' => 'Interno'])->id;
        $trainings['Instalações, Quadros e Redes Eléctricas e Fotovoltaicas Residenciais e Fabris de Peq. Ou Média Dimensão 16h'] = Training::create(['title' => 'Instalações, Quadros e Redes Eléctricas e Fotovoltaicas Residenciais e Fabris de Peq. Ou Média Dimensão 16h', 'provider' => 'Interno'])->id;
        $trainings['Introdução à Gestão de Energia_COD10972'] = Training::create(['title' => 'Introdução à Gestão de Energia_COD10972', 'provider' => 'Interno'])->id;
        $trainings['LIT (VÁLIDO)'] = Training::create(['title' => 'LIT (VÁLIDO)', 'provider' => 'Interno'])->id;
        $trainings['Ligação de Grupos Electrogéneos 14h (EDP)'] = Training::create(['title' => 'Ligação de Grupos Electrogéneos 14h (EDP)', 'provider' => 'Interno'])->id;
        $trainings['Ligação de Meios Auxiliares _COD8055'] = Training::create(['title' => 'Ligação de Meios Auxiliares _COD8055', 'provider' => 'Interno'])->id;
        $trainings['Manobra e Parqueamento-Regras_COD3916'] = Training::create(['title' => 'Manobra e Parqueamento-Regras_COD3916', 'provider' => 'Interno'])->id;
        $trainings['Manobra e Parqueamento-Regras_COD3916.1'] = Training::create(['title' => 'Manobra e Parqueamento-Regras_COD3916.1', 'provider' => 'Interno'])->id;
        $trainings['Manobrador de Máquinas 16h'] = Training::create(['title' => 'Manobrador de Máquinas 16h', 'provider' => 'Interno'])->id;
        $trainings['Manutenção e Reparaç.de avarias em redes BT e IP_COD8057'] = Training::create(['title' => 'Manutenção e Reparaç.de avarias em redes BT e IP_COD8057', 'provider' => 'Interno'])->id;
        $trainings['Mobilidade Eléctrica EDP'] = Training::create(['title' => 'Mobilidade Eléctrica EDP', 'provider' => 'Interno'])->id;
        $trainings['Montagem de Acessórios p/Redes Subterrâneas MT 36h'] = Training::create(['title' => 'Montagem de Acessórios p/Redes Subterrâneas MT 36h', 'provider' => 'Interno'])->id;
        $trainings['Módulos Solares Fotovoltaicos _COD4588 50h'] = Training::create(['title' => 'Módulos Solares Fotovoltaicos _COD4588 50h', 'provider' => 'Interno'])->id;
        $trainings['Noções de Higiene e Segurança no trabalho-Electricidade e electrónica_COD6040'] = Training::create(['title' => 'Noções de Higiene e Segurança no trabalho-Electricidade e electrónica_COD6040', 'provider' => 'Interno'])->id;
        $trainings['Operador de Máquinas Agrícolas 1.200h'] = Training::create(['title' => 'Operador de Máquinas Agrícolas 1.200h', 'provider' => 'Interno'])->id;
        $trainings['Operadores de Grupos Electrogéneos 18h'] = Training::create(['title' => 'Operadores de Grupos Electrogéneos 18h', 'provider' => 'Interno'])->id;
        $trainings['Operação de Equipamentos de Corte: Motosserras e Roçadores 12h/8h'] = Training::create(['title' => 'Operação de Equipamentos de Corte: Motosserras e Roçadores 12h/8h', 'provider' => 'Interno'])->id;
        $trainings['Planeamento e Gestão de Obra 20h'] = Training::create(['title' => 'Planeamento e Gestão de Obra 20h', 'provider' => 'Interno'])->id;
        $trainings['Plataformas Elevatórias    8h'] = Training::create(['title' => 'Plataformas Elevatórias    8h', 'provider' => 'Interno'])->id;
        $trainings['Postos de Transformação de Energia Eléctrica_COD6042'] = Training::create(['title' => 'Postos de Transformação de Energia Eléctrica_COD6042', 'provider' => 'Interno'])->id;
        $trainings['Prevenção de Riscos Eléctricos 30h'] = Training::create(['title' => 'Prevenção de Riscos Eléctricos 30h', 'provider' => 'Interno'])->id;
        $trainings['Prevenção de Riscos Eléctricos 30h.1'] = Training::create(['title' => 'Prevenção de Riscos Eléctricos 30h.1', 'provider' => 'Interno'])->id;
        $trainings['Produção, Transporte e Distribuição de Energia Eléctrica_COD 5331'] = Training::create(['title' => 'Produção, Transporte e Distribuição de Energia Eléctrica_COD 5331', 'provider' => 'Interno'])->id;
        $trainings['Projeto de Sistema Solar Fotovoltaico-CONSTRUÇÃO_          COD4590 50h'] = Training::create(['title' => 'Projeto de Sistema Solar Fotovoltaico-CONSTRUÇÃO_          COD4590 50h', 'provider' => 'Interno'])->id;
        $trainings['Projeto de Sistema Solar Fotovoltaico-INSTALAÇÃO _COD4591'] = Training::create(['title' => 'Projeto de Sistema Solar Fotovoltaico-INSTALAÇÃO _COD4591', 'provider' => 'Interno'])->id;
        $trainings['Recertificação p/Tripulantes de Ambulância de Transporte c/SBV DAE 25h'] = Training::create(['title' => 'Recertificação p/Tripulantes de Ambulância de Transporte c/SBV DAE 25h', 'provider' => 'Interno'])->id;
        $trainings['Reciclagem TET-MT_Método de Intervenção à Distância até 30kV (Equipas Pesadas) 48h'] = Training::create(['title' => 'Reciclagem TET-MT_Método de Intervenção à Distância até 30kV (Equipas Pesadas) 48h', 'provider' => 'Interno'])->id;
        $trainings['Reciclagem TET/BT 30h/35h/46h'] = Training::create(['title' => 'Reciclagem TET/BT 30h/35h/46h', 'provider' => 'Interno'])->id;
        $trainings['Reconhecimento de Técnico Responsável de Instalações Elétricas de Serviços Particular'] = Training::create(['title' => 'Reconhecimento de Técnico Responsável de Instalações Elétricas de Serviços Particular', 'provider' => 'Interno'])->id;
        $trainings['Redes Aéreas         AT e MT - caracterização _COD8048'] = Training::create(['title' => 'Redes Aéreas         AT e MT - caracterização _COD8048', 'provider' => 'Interno'])->id;
        $trainings['Redes Inteligentes_COD8078'] = Training::create(['title' => 'Redes Inteligentes_COD8078', 'provider' => 'Interno'])->id;
        $trainings['Redes Subterrâneas AT e MT-caracterização _COD8050'] = Training::create(['title' => 'Redes Subterrâneas AT e MT-caracterização _COD8050', 'provider' => 'Interno'])->id;
        $trainings['Responsável TET MT MID 30kV (AQTSE)'] = Training::create(['title' => 'Responsável TET MT MID 30kV (AQTSE)', 'provider' => 'Interno'])->id;
        $trainings['Retroescav /Escavadora 8h'] = Training::create(['title' => 'Retroescav /Escavadora 8h', 'provider' => 'Interno'])->id;
        $trainings['STAC- Segurança em Trabalhos e Altura-Coberturas, Telhados e Fachadas 8h/16h/14h'] = Training::create(['title' => 'STAC- Segurança em Trabalhos e Altura-Coberturas, Telhados e Fachadas 8h/16h/14h', 'provider' => 'Interno'])->id;
        $trainings['Segurança Eléctrica _COD6044'] = Training::create(['title' => 'Segurança Eléctrica _COD6044', 'provider' => 'Interno'])->id;
        $trainings['Segurança de Manutenção e Conservação de Postos de Transformação em Tensão até 30KV'] = Training::create(['title' => 'Segurança de Manutenção e Conservação de Postos de Transformação em Tensão até 30KV', 'provider' => 'Interno'])->id;
        $trainings['Sensibilização 1ºos Socorros 7h/8h'] = Training::create(['title' => 'Sensibilização 1ºos Socorros 7h/8h', 'provider' => 'Interno'])->id;
        $trainings['Sistema Integrado de Gestão QA 20h'] = Training::create(['title' => 'Sistema Integrado de Gestão QA 20h', 'provider' => 'Interno'])->id;
        $trainings['Sistemas Solares Fotovoltaicos _COD4587 50h'] = Training::create(['title' => 'Sistemas Solares Fotovoltaicos _COD4587 50h', 'provider' => 'Interno'])->id;
        $trainings['Socorrismo e Resgate do Acidentado 24h'] = Training::create(['title' => 'Socorrismo e Resgate do Acidentado 24h', 'provider' => 'Interno'])->id;
        $trainings['Suporte Básico de Vida 4h'] = Training::create(['title' => 'Suporte Básico de Vida 4h', 'provider' => 'Interno'])->id;
        $trainings['TAR 21h'] = Training::create(['title' => 'TAR 21h', 'provider' => 'Interno'])->id;
        $trainings['TAR-Baixa Tensão 14h'] = Training::create(['title' => 'TAR-Baixa Tensão 14h', 'provider' => 'Interno'])->id;
        $trainings['TET-Limpeza e Pequena Conservação de Postos de Transformação até 30kV'] = Training::create(['title' => 'TET-Limpeza e Pequena Conservação de Postos de Transformação até 30kV', 'provider' => 'Interno'])->id;
        $trainings['TET-Limpeza e Pequena Conservação em Tensão de PT 48h'] = Training::create(['title' => 'TET-Limpeza e Pequena Conservação em Tensão de PT 48h', 'provider' => 'Interno'])->id;
        $trainings['TET/BT-Redes     90h/119h/120h'] = Training::create(['title' => 'TET/BT-Redes     90h/119h/120h', 'provider' => 'Interno'])->id;
        $trainings['TET/MT Método de Intervenção à Distância até 30KV-60h/360h/420'] = Training::create(['title' => 'TET/MT Método de Intervenção à Distância até 30KV-60h/360h/420', 'provider' => 'Interno'])->id;
        $trainings['Tecnologia dos Materiais Electrícos Industriais_COD 5359'] = Training::create(['title' => 'Tecnologia dos Materiais Electrícos Industriais_COD 5359', 'provider' => 'Interno'])->id;
        $trainings['Trab. Em Altura - Montagem de Paineis Solares Fotovoltaicos 8h'] = Training::create(['title' => 'Trab. Em Altura - Montagem de Paineis Solares Fotovoltaicos 8h', 'provider' => 'Interno'])->id;
        $trainings['Trabalhos em Tensão _COD8059'] = Training::create(['title' => 'Trabalhos em Tensão _COD8059', 'provider' => 'Interno'])->id;
        $trainings['Técnico de Segurança e Higiene do Trabalho'] = Training::create(['title' => 'Técnico de Segurança e Higiene do Trabalho', 'provider' => 'Interno'])->id;
        $trainings['Verificação e Inspeção de Equipamento 7h'] = Training::create(['title' => 'Verificação e Inspeção de Equipamento 7h', 'provider' => 'Interno'])->id;
        $trainings['e-Formador | e-Tutor 25h'] = Training::create(['title' => 'e-Formador | e-Tutor 25h', 'provider' => 'Interno'])->id;

        $empMap = [];

        $emp = Employee::create([
            'code'          => 'FUN0001',
            'first_name'    => 'José',
            'last_name'     => 'da Guia de Passos Canão',
            'email'         => 'jos.da.guia.de.passos.can.o@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Viana do Castelo',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions['DIRECTOR'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0001'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0002',
            'first_name'    => 'Augusto',
            'last_name'     => 'José Gonçalves de Passos Canão',
            'email'         => 'augusto.jos.gon.alves.de.passos.can.o@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Santa Marta de Portuzelo',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'active',
            'department_id' => $dept->id,
            'position_id'   => $positions['Gerência'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0002'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0003',
            'first_name'    => 'Maria',
            'last_name'     => 'Augusta Gonçalves Canão',
            'email'         => 'maria.augusta.gon.alves.can.o@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Santa Maria Maior, Viana do Castelo',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'active',
            'department_id' => $dept->id,
            'position_id'   => $positions['Gerência'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0003'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0004',
            'first_name'    => 'José',
            'last_name'     => 'Cipriano Gonçalves Canão',
            'email'         => 'jos.cipriano.gon.alves.can.o@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Viana do Castelo',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'active',
            'department_id' => $dept->id,
            'position_id'   => $positions['DIRECTOR DE SERVIÇOS'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0004'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0005',
            'first_name'    => 'Luís',
            'last_name'     => 'Miguel Meixedo Afonso',
            'email'         => 'lu.s.miguel.meixedo.afonso@hreminho.pt',
            'date_of_birth' => '1977-06-21',
            'nationality'   => null,
            'address'       => 'Viana do Castelo',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'active',
            'department_id' => $dept->id,
            'position_id'   => $positions['CHEFE DE EQUIPA (EL)'],
            'sector_id'     => $sectors['BT+Obras Particulares'],
        ]);
        $empMap['FUN0005'] = $emp->id;
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['TET/BT-Redes     90h/119h/120h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Reciclagem TET/BT 30h/35h/46h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['TAR-Baixa Tensão 14h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Tecnologia dos Materiais Electrícos Industriais_COD 5359'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Ambiente, Segurança, Higiene e Súde no Trabalho-Conceito Básicos_COD0349'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Postos de Transformação de Energia Eléctrica_COD6042'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Manutenção e Reparaç.de avarias em redes BT e IP_COD8057'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Retroescav /Escavadora 8h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Gruas 20h/8h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Plataformas Elevatórias    8h'], 'status' => 'completed']);

        $emp = Employee::create([
            'code'          => 'FUN0006',
            'first_name'    => 'Carlos',
            'last_name'     => 'Alberto Gonçalves Pereira',
            'email'         => 'carlos.alberto.gon.alves.pereira@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Tregosa',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'active',
            'department_id' => $dept->id,
            'position_id'   => $positions['CHEFE DE EQUIPA (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0006'] = $emp->id;
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Desenvolver a comunicação interpessoal 40h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Colocação em Serviço e Circulação Obra/Estrada-Normas_COD3915'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Manobra e Parqueamento-Regras_COD3916.1'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Projeto de Sistema Solar Fotovoltaico-CONSTRUÇÃO_          COD4590 50h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Projeto de Sistema Solar Fotovoltaico-INSTALAÇÃO _COD4591'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Redes Inteligentes_COD8078'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Eficiência Energética e Energia Renováveis_COD9282'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Introdução à Gestão de Energia_COD10972'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Retroescav /Escavadora 8h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Empilhadores 20h/8h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Gruas 20h/8h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Plataformas Elevatórias    8h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['FP_Giratórias-Abertura de Valas e transporte de terras 20h/25h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['CAM 35h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Verificação e Inspeção de Equipamento 7h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['1ºS Socorros 12H/ 16h/24h/25h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Trab. Em Altura - Montagem de Paineis Solares Fotovoltaicos 8h'], 'status' => 'completed']);

        $emp = Employee::create([
            'code'          => 'FUN0007',
            'first_name'    => 'António',
            'last_name'     => 'Antunes Ferreira',
            'email'         => 'ant.nio.antunes.ferreira@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Viana do Castelo',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'active',
            'department_id' => $dept->id,
            'position_id'   => $positions['TOC'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0007'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0008',
            'first_name'    => 'Pedro',
            'last_name'     => 'Miguel Lima Afonso',
            'email'         => 'pedro.miguel.lima.afonso@hreminho.pt',
            'date_of_birth' => '1977-11-10',
            'nationality'   => null,
            'address'       => 'Meadela, Viana do Castelo',
            'work_location' => null,
            'hire_date'     => '1996-06-11',
            'status'        => 'active',
            'department_id' => $dept->id,
            'position_id'   => $positions['OFICIAL ELECTRICISTA (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0008'] = $emp->id;
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['TET/BT-Redes     90h/119h/120h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Reciclagem TET/BT 30h/35h/46h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['TAR 21h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['STAC- Segurança em Trabalhos e Altura-Coberturas, Telhados e Fachadas 8h/16h/14h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Contagem de Energia BTN 7h/14h/18h/21h/28h'], 'status' => 'completed']);

        $emp = Employee::create([
            'code'          => 'FUN0009',
            'first_name'    => 'Paula',
            'last_name'     => 'Maria Oliveira Amoroso Sá',
            'email'         => 'paula.maria.oliveira.amoroso.s@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Portuzelo, Viana do Castelo',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'active',
            'department_id' => $dept->id,
            'position_id'   => $positions['ESCRITURÁRIO DE 1. (ESC)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0009'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0010',
            'first_name'    => 'Artur',
            'last_name'     => 'Manuel Maceiro Vieitas',
            'email'         => 'artur.manuel.maceiro.vieitas@hreminho.pt',
            'date_of_birth' => '1962-12-16',
            'nationality'   => null,
            'address'       => 'Carreço',
            'work_location' => null,
            'hire_date'     => '1999-01-04',
            'status'        => 'active',
            'department_id' => $dept->id,
            'position_id'   => $positions['ENCARREGADO (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0010'] = $emp->id;
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Electricista TET BT (AQTSE)'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Electricista de Redes BT (AQTSE)'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['TET/BT-Redes     90h/119h/120h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Consignação de Instalações Eléctricas 18h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Prevenção de Riscos Eléctricos 30h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Produção, Transporte e Distribuição de Energia Eléctrica_COD 5331'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Tecnologia dos Materiais Electrícos Industriais_COD 5359'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Ambiente, Segurança, Higiene e Súde no Trabalho-Conceito Básicos_COD0349'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Instalações Eléctricas Coletiva e recebendo Público_COD5349'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Redes Inteligentes_COD8078'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Eficiência Energética e Energia Renováveis_COD9282'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Operação de Equipamentos de Corte: Motosserras e Roçadores 12h/8h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Operadores de Grupos Electrogéneos 18h'], 'status' => 'completed']);

        $emp = Employee::create([
            'code'          => 'FUN0012',
            'first_name'    => 'António',
            'last_name'     => 'Pedro Oliveira Sampaio Carvalho',
            'email'         => 'ant.nio.pedro.oliveira.sampaio.carvalho@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Meadela, Viana do Castelo',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions['ESCRITURÁRIO DE 1. (ESC)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0012'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0013',
            'first_name'    => 'João',
            'last_name'     => 'Manuel da Cunha Felgueiras',
            'email'         => 'jo.o.manuel.da.cunha.felgueiras@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Viana do Castelo',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions['CAIXEIRO DE 1. (COM)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0013'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0014',
            'first_name'    => 'José',
            'last_name'     => 'Domingos Nunes Sousa',
            'email'         => 'jos.domingos.nunes.sousa@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Viana do Castelo',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'active',
            'department_id' => $dept->id,
            'position_id'   => $positions['CHEFE DE DEPARTAMENTO'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0014'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0016',
            'first_name'    => 'Adriano',
            'last_name'     => 'Arieira Rodrigues',
            'email'         => 'adriano.arieira.rodrigues@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Perre',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions['CHEFE DE EQUIPA (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0016'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0017',
            'first_name'    => 'Aires',
            'last_name'     => 'Manuel Maciel Carvalho',
            'email'         => 'aires.manuel.maciel.carvalho@hreminho.pt',
            'date_of_birth' => '1966-01-09',
            'nationality'   => null,
            'address'       => 'Viana do Castelo',
            'work_location' => 'Viana',
            'hire_date'     => '2010-01-01',
            'status'        => 'active',
            'department_id' => $dept->id,
            'position_id'   => $positions['TECNICO DE REFRIGERACAO E CLIMATIZACAO'],
            'sector_id'     => $sectors['AVAC'],
        ]);
        $empMap['FUN0017'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0018',
            'first_name'    => 'César',
            'last_name'     => 'Manuel da Costa Castro',
            'email'         => 'c.sar.manuel.da.costa.castro@hreminho.pt',
            'date_of_birth' => '1988-01-05',
            'nationality'   => null,
            'address'       => 'Santa Cristina de Arões',
            'work_location' => 'Guimarães',
            'hire_date'     => '2017-06-01',
            'status'        => 'active',
            'department_id' => $dept->id,
            'position_id'   => $positions['OFICIAL ELECTRICISTA (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0018'] = $emp->id;
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Electricista TET BT (AQTSE)'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['LIT (VÁLIDO)'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['TET/BT-Redes     90h/119h/120h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Executantes em Contagem em Energia BT E MT 18h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Manutenção e Reparaç.de avarias em redes BT e IP_COD8057'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Contagem BTN_COD8058'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Trabalhos em Tensão _COD8059'], 'status' => 'completed']);

        $emp = Employee::create([
            'code'          => 'FUN0020',
            'first_name'    => 'Carlos',
            'last_name'     => 'Alberto Arieira Rodrigues',
            'email'         => 'carlos.alberto.arieira.rodrigues@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Perre, Viana do Castelo',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions['ENCARREGADO (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0020'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0021',
            'first_name'    => 'Cipriano',
            'last_name'     => 'Veloso Barbosa',
            'email'         => 'cipriano.veloso.barbosa@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Rebordões Souto',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'active',
            'department_id' => $dept->id,
            'position_id'   => $positions['CHEFE DE EQUIPA (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0021'] = $emp->id;
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Desenvolver a comunicação interpessoal 40h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['TET/BT-Redes     90h/119h/120h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Reciclagem TET/BT 30h/35h/46h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Execução de Caixas MT 60h/30h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Consignação de Instalações Eléctricas 18h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Prevenção de Riscos Eléctricos 30h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Produção, Transporte e Distribuição de Energia Eléctrica_COD 5331'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Tecnologia dos Materiais Electrícos Industriais_COD 5359'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Ambiente, Segurança, Higiene e Súde no Trabalho-Conceito Básicos_COD0349'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Instalações Eléctricas Coletiva e recebendo Público_COD5349'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Colocação em Serviço e Circulação Obra/Estrada-Normas_COD3915'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Projeto de Sistema Solar Fotovoltaico-CONSTRUÇÃO_          COD4590 50h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Projeto de Sistema Solar Fotovoltaico-INSTALAÇÃO _COD4591'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Postos de Transformação de Energia Eléctrica_COD6042'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Segurança Eléctrica _COD6044'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Ligação de Meios Auxiliares _COD8055'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Manutenção e Reparaç.de avarias em redes BT e IP_COD8057'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Redes Inteligentes_COD8078'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Electricidade Geral _COD0932 50h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Gruas 20h/8h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['CAM 35h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Operação de Equipamentos de Corte: Motosserras e Roçadores 12h/8h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Operadores de Grupos Electrogéneos 18h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['1ºS Socorros 12H/ 16h/24h/25h'], 'status' => 'completed']);

        $emp = Employee::create([
            'code'          => 'FUN0022',
            'first_name'    => 'Daciano',
            'last_name'     => 'José de Castro Araújo',
            'email'         => 'daciano.jos.de.castro.ara.jo@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Viana do Castelo',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions['OFICIAL PRINCIPAL (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0022'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0023',
            'first_name'    => 'António',
            'last_name'     => 'Fernandes Mesquita',
            'email'         => 'ant.nio.fernandes.mesquita@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Viana do Castelo',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions['OFICIAL ELECTRICISTA (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0023'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0024',
            'first_name'    => 'Daniel',
            'last_name'     => 'José de Sousa Passos Lima',
            'email'         => 'daniel.jos.de.sousa.passos.lima@hreminho.pt',
            'date_of_birth' => '1973-10-29',
            'nationality'   => null,
            'address'       => 'Meadela, Viana do Castelo',
            'work_location' => 'Guimarães',
            'hire_date'     => '1994-01-31',
            'status'        => 'active',
            'department_id' => $dept->id,
            'position_id'   => $positions['ENCARREGADO (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0024'] = $emp->id;
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Desenvolver a comunicação interpessoal 40h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Electricista de Redes BT (AQTSE)'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['LIT (VÁLIDO)'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['TET/BT-Redes     90h/119h/120h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['TET-Limpeza e Pequena Conservação em Tensão de PT 48h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Execução de Caixas MT 60h/30h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Consignação de Instalações Eléctricas 18h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Prevenção de Riscos Eléctricos 30h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Produção, Transporte e Distribuição de Energia Eléctrica_COD 5331'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Tecnologia dos Materiais Electrícos Industriais_COD 5359'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Ambiente, Segurança, Higiene e Súde no Trabalho-Conceito Básicos_COD0349'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Instalações Eléctricas Coletiva e recebendo Público_COD5349'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Sistemas Solares Fotovoltaicos _COD4587 50h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Projeto de Sistema Solar Fotovoltaico-CONSTRUÇÃO_          COD4590 50h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Projeto de Sistema Solar Fotovoltaico-INSTALAÇÃO _COD4591'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Noções de Higiene e Segurança no trabalho-Electricidade e electrónica_COD6040'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Postos de Transformação de Energia Eléctrica_COD6042'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Segurança Eléctrica _COD6044'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Ligação de Meios Auxiliares _COD8055'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Manutenção e Reparaç.de avarias em redes BT e IP_COD8057'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Contagem BTN_COD8058'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Trabalhos em Tensão _COD8059'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Redes Inteligentes_COD8078'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Electricidade Geral _COD0932 50h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Introdução à Gestão de Energia_COD10972'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Operadores de Grupos Electrogéneos 18h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['1ºS Socorros 12H/ 16h/24h/25h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Sensibilização 1ºos Socorros 7h/8h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Combate a Incêndio evacuação 7h'], 'status' => 'completed']);

        $emp = Employee::create([
            'code'          => 'FUN0025',
            'first_name'    => 'Ernesto',
            'last_name'     => 'Franco Carvalho Pereira',
            'email'         => 'ernesto.franco.carvalho.pereira@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Viana do Castelo',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions['OFICIAL ELECTRICISTA (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0025'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0026',
            'first_name'    => 'Fernando',
            'last_name'     => 'Manuel Araújo Pires',
            'email'         => 'fernando.manuel.ara.jo.pires@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Viana do Castelo',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions['OFICIAL ELECTRICISTA (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0026'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0027',
            'first_name'    => 'José',
            'last_name'     => 'Maria Garcia',
            'email'         => 'jos.maria.garcia@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Chafé',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions['CONDUTOR MANOBRADOR DE EQUIPAMENTOS INDUSTRIAIS (NIVEL III)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0027'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0031',
            'first_name'    => 'José',
            'last_name'     => 'Daniel dos Santos Penaforte',
            'email'         => 'jos.daniel.dos.santos.penaforte@hreminho.pt',
            'date_of_birth' => '1962-01-05',
            'nationality'   => null,
            'address'       => 'Viana do Castelo',
            'work_location' => null,
            'hire_date'     => '1987-09-30',
            'status'        => 'active',
            'department_id' => $dept->id,
            'position_id'   => $positions['OFICIAL ELECTRICISTA (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0031'] = $emp->id;
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Técnico de Segurança e Higiene do Trabalho'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Electricista TET BT (AQTSE)'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Electricista de Redes BT (AQTSE)'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Electricista de Contagem BTN e Operações Comerciais (AQTSE)'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['LIT (VÁLIDO)'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['TET/BT-Redes     90h/119h/120h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Contagens (BTE) 14h/18h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Contagem de Energia BTN 7h/14h/18h/21h/28h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Executantes em Contagem em Energia BT E MT 18h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Noções de Higiene e Segurança no trabalho-Electricidade e electrónica_COD6040'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Trabalhos em Tensão _COD8059'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Redes Inteligentes_COD8078'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Eficiência Energética e Energia Renováveis_COD9282'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Plataformas Elevatórias    8h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Implementação e Organização do Serviço de Higiene e Segurança Industrial 14h'], 'status' => 'completed']);

        $emp = Employee::create([
            'code'          => 'FUN0034',
            'first_name'    => 'José',
            'last_name'     => 'Luís Cruz Gramacho Silva',
            'email'         => 'jos.lu.s.cruz.gramacho.silva@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Viana do Castelo',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'active',
            'department_id' => $dept->id,
            'position_id'   => $positions['PRE OFICIAL DO 2.ANO (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0034'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0035',
            'first_name'    => 'José',
            'last_name'     => 'Luís Parente Vieira',
            'email'         => 'jos.lu.s.parente.vieira@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Viana do Castelo',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions['ENCARREGADO (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0035'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0036',
            'first_name'    => 'José',
            'last_name'     => 'Maciel de Carvalho',
            'email'         => 'jos.maciel.de.carvalho@hreminho.pt',
            'date_of_birth' => '1961-06-21',
            'nationality'   => null,
            'address'       => 'Viana do Castelo',
            'work_location' => 'Guimarães',
            'hire_date'     => '1980-04-01',
            'status'        => 'active',
            'department_id' => $dept->id,
            'position_id'   => $positions['ENCARREGADO (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0036'] = $emp->id;
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Desenvolver a comunicação interpessoal 40h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['TET/BT-Redes     90h/119h/120h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['TET-Limpeza e Pequena Conservação de Postos de Transformação até 30kV'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Execução de Caixas MT 60h/30h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Consignação de Instalações Eléctricas 18h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Noções de Higiene e Segurança no trabalho-Electricidade e electrónica_COD6040'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Postos de Transformação de Energia Eléctrica_COD6042'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Ligação de Meios Auxiliares _COD8055'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Manutenção e Reparaç.de avarias em redes BT e IP_COD8057'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Electricidade Geral _COD0932 50h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Eficiência Energética e Energia Renováveis_COD9282'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Retroescav /Escavadora 8h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Gruas 20h/8h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Operadores de Grupos Electrogéneos 18h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Sensibilização 1ºos Socorros 7h/8h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Recertificação p/Tripulantes de Ambulância de Transporte c/SBV DAE 25h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Combate a Incêndio evacuação 7h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Colocação e Retirada de Paineis de Subestações, Postos de Corte e Seccionamento em Regime Especial de Exploração 7H'], 'status' => 'completed']);

        $emp = Employee::create([
            'code'          => 'FUN0038',
            'first_name'    => 'Dalila',
            'last_name'     => 'Dias Alves',
            'email'         => 'dalila.dias.alves@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Mujães',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'active',
            'department_id' => $dept->id,
            'position_id'   => $positions['CAIXEIRO DE 1. (COM)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0038'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0039',
            'first_name'    => 'Luís',
            'last_name'     => 'Eugénio Martins Baptista Bezerra',
            'email'         => 'lu.s.eug.nio.martins.baptista.bezerra@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Darque, Viana do Castelo',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'active',
            'department_id' => $dept->id,
            'position_id'   => $positions['CHEFE DE EQUIPA (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0039'] = $emp->id;
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['TET/BT-Redes     90h/119h/120h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Gruas 20h/8h'], 'status' => 'completed']);

        $emp = Employee::create([
            'code'          => 'FUN0040',
            'first_name'    => 'Fernando',
            'last_name'     => 'Lima Gonçalves Novo',
            'email'         => 'fernando.lima.gon.alves.novo@hreminho.pt',
            'date_of_birth' => '1964-02-22',
            'nationality'   => null,
            'address'       => 'Serreleis',
            'work_location' => null,
            'hire_date'     => '1994-01-10',
            'status'        => 'active',
            'department_id' => $dept->id,
            'position_id'   => $positions['ENCARREGADO (EL)'],
            'sector_id'     => $sectors['BT+MT'],
        ]);
        $empMap['FUN0040'] = $emp->id;
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Electricista TET BT (AQTSE)'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Electricista de Redes BT (AQTSE)'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['LIT (VÁLIDO)'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['TET/BT-Redes     90h/119h/120h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['TAR-Baixa Tensão 14h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Colocação em Serviço e Circulação Obra/Estrada-Normas_COD3915'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Postos de Transformação de Energia Eléctrica_COD6042'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Segurança Eléctrica _COD6044'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Manutenção e Reparaç.de avarias em redes BT e IP_COD8057'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Contagem BTN_COD8058'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Trabalhos em Tensão _COD8059'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Gruas 20h/8h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Prevenção de Riscos Eléctricos 30h.1'], 'status' => 'completed']);

        $emp = Employee::create([
            'code'          => 'FUN0041',
            'first_name'    => 'Manuel',
            'last_name'     => 'Luís Parente Martins Rufo',
            'email'         => 'manuel.lu.s.parente.martins.rufo@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Perre',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions['Encarregado'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0041'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0044',
            'first_name'    => 'Fernando',
            'last_name'     => 'Oliveira Freitas',
            'email'         => 'fernando.oliveira.freitas@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Moreira do Rei, Fafe',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions['OFICIAL ELECTRICISTA (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0044'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0046',
            'first_name'    => 'Ricardo',
            'last_name'     => 'Fernando Porto',
            'email'         => 'ricardo.fernando.porto@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Caminha',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions['OFICIAL ELECTRICISTA (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0046'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0047',
            'first_name'    => 'Rui',
            'last_name'     => 'Manuel da Mota Pereira Azevedo',
            'email'         => 'rui.manuel.da.mota.pereira.azevedo@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Vila Verde, Braga',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions[array_key_first($positions)],
            'sector_id'     => null,
        ]);
        $empMap['FUN0047'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0049',
            'first_name'    => 'José',
            'last_name'     => 'Manuel de Oliveira Grilo',
            'email'         => 'jos.manuel.de.oliveira.grilo@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Carreço, Viana do Castelo',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions['PRE OFICIAL DO 2.ANO (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0049'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0050',
            'first_name'    => 'Vítor',
            'last_name'     => 'Miguel Ramos Gonçalves',
            'email'         => 'v.tor.miguel.ramos.gon.alves@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Fafe - Fafe',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'active',
            'department_id' => $dept->id,
            'position_id'   => $positions['CHEFE DE EQUIPA (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0050'] = $emp->id;
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['TET/BT-Redes     90h/119h/120h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Reciclagem TET/BT 30h/35h/46h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['TAR 21h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['INST CONT BTN_ Instalador de Contagem Baixa Tensão Normal (AQTSE)'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Contagens (BTE) 14h/18h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Prevenção de Riscos Eléctricos 30h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Instalações Eléctricas Coletivas e Recebendo Público- Projeto_COD5349'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['1ºS Socorros 12H/ 16h/24h/25h'], 'status' => 'completed']);

        $emp = Employee::create([
            'code'          => 'FUN0051',
            'first_name'    => 'Norberto',
            'last_name'     => 'Evangelista Goncalves Nicolau',
            'email'         => 'norberto.evangelista.goncalves.nicolau@hreminho.pt',
            'date_of_birth' => '1965-05-11',
            'nationality'   => null,
            'address'       => 'Viana do Castelo',
            'work_location' => null,
            'hire_date'     => '1988-02-01',
            'status'        => 'active',
            'department_id' => $dept->id,
            'position_id'   => $positions['OFICIAL ELECTRICISTA (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0051'] = $emp->id;
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Electricista TET BT (AQTSE)'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Electricista de Redes BT (AQTSE)'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['LIT (VÁLIDO)'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['TET/BT-Redes     90h/119h/120h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['TAR-Baixa Tensão 14h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Contagem de Energia BTN 7h/14h/18h/21h/28h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Colocação em Serviço e Circulação Obra/Estrada-Normas_COD3915'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Manobra e Parqueamento-Regras_COD3916'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Sistemas Solares Fotovoltaicos _COD4587 50h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Módulos Solares Fotovoltaicos _COD4588 50h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Projeto de Sistema Solar Fotovoltaico-CONSTRUÇÃO_          COD4590 50h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Projeto de Sistema Solar Fotovoltaico-INSTALAÇÃO _COD4591'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Noções de Higiene e Segurança no trabalho-Electricidade e electrónica_COD6040'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Contagem BTN_COD8058'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Trabalhos em Tensão _COD8059'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Electricidade Geral _COD0932 50h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Gruas 20h/8h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Plataformas Elevatórias    8h'], 'status' => 'completed']);

        $emp = Employee::create([
            'code'          => 'FUN0052',
            'first_name'    => 'Rui',
            'last_name'     => 'Filipe Araújo Rocha',
            'email'         => 'rui.filipe.ara.jo.rocha@hreminho.pt',
            'date_of_birth' => '1962-09-14',
            'nationality'   => null,
            'address'       => 'Perre',
            'work_location' => null,
            'hire_date'     => '1988-05-26',
            'status'        => 'active',
            'department_id' => $dept->id,
            'position_id'   => $positions['OFICIAL ELECTRICISTA (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0052'] = $emp->id;
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['LIT (VÁLIDO)'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['TET/BT-Redes     90h/119h/120h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Reciclagem TET/BT 30h/35h/46h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['TET-Limpeza e Pequena Conservação em Tensão de PT 48h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['TAR-Baixa Tensão 14h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Contagens (BTE) 14h/18h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Montagem de Acessórios p/Redes Subterrâneas MT 36h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Produção, Transporte e Distribuição de Energia Eléctrica_COD 5331'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Tecnologia dos Materiais Electrícos Industriais_COD 5359'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Ambiente, Segurança, Higiene e Súde no Trabalho-Conceito Básicos_COD0349'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Instalações Eléctricas Coletiva e recebendo Público_COD5349'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Colocação em Serviço e Circulação Obra/Estrada-Normas_COD3915'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Sistemas Solares Fotovoltaicos _COD4587 50h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Módulos Solares Fotovoltaicos _COD4588 50h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Projeto de Sistema Solar Fotovoltaico-CONSTRUÇÃO_          COD4590 50h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Projeto de Sistema Solar Fotovoltaico-INSTALAÇÃO _COD4591'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Noções de Higiene e Segurança no trabalho-Electricidade e electrónica_COD6040'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Contagem BTN_COD8058'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Trabalhos em Tensão _COD8059'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Electricidade Geral _COD0932 50h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Introdução à Gestão de Energia_COD10972'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Plataformas Elevatórias    8h'], 'status' => 'completed']);

        $emp = Employee::create([
            'code'          => 'FUN0053',
            'first_name'    => 'Rui',
            'last_name'     => 'Jorge Gonçalves Pires',
            'email'         => 'rui.jorge.gon.alves.pires@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Viana do Castelo',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions['CHEFE DE EQUIPA (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0053'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0055',
            'first_name'    => 'Márcio',
            'last_name'     => 'Filipe Pires Bezerra',
            'email'         => 'm.rcio.filipe.pires.bezerra@hreminho.pt',
            'date_of_birth' => '1983-07-13',
            'nationality'   => null,
            'address'       => 'Viana do Castelo',
            'work_location' => null,
            'hire_date'     => '2004-03-08',
            'status'        => 'active',
            'department_id' => $dept->id,
            'position_id'   => $positions['OFICIAL ELECTRICISTA (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0055'] = $emp->id;
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Electricista TET BT (AQTSE)'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Electricista de Redes BT (AQTSE)'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Electricista de Assistência à Rede e Clientes (ARC) - BT (AQTSE)'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Electricista de Contagem BTN e Operações Comerciais (AQTSE)'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['LIT (VÁLIDO)'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['TET/BT-Redes     90h/119h/120h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['TAR-Baixa Tensão 14h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Contagem de Energia BTN 7h/14h/18h/21h/28h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Plataformas Elevatórias    8h'], 'status' => 'completed']);

        $emp = Employee::create([
            'code'          => 'FUN0056',
            'first_name'    => 'Paulo',
            'last_name'     => 'Jorge Machado Silva',
            'email'         => 'paulo.jorge.machado.silva@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Viana do Castelo',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions['OFICIAL PRINCIPAL (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0056'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0057',
            'first_name'    => 'Nuno',
            'last_name'     => 'Miguel Rodrigues Peixoto',
            'email'         => 'nuno.miguel.rodrigues.peixoto@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Travassós, Fafe',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions[array_key_first($positions)],
            'sector_id'     => null,
        ]);
        $empMap['FUN0057'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0058',
            'first_name'    => 'Mário',
            'last_name'     => 'Parente Martins Rufo',
            'email'         => 'm.rio.parente.martins.rufo@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Perre',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions['MONTADOR DE CANALIZACOES/INSTALADOR DE REDES (MET)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0058'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0063',
            'first_name'    => 'Henrique',
            'last_name'     => 'Samuel Armada Rebelo',
            'email'         => 'henrique.samuel.armada.rebelo@hreminho.pt',
            'date_of_birth' => '1982-04-24',
            'nationality'   => null,
            'address'       => 'Pena - Ribeira',
            'work_location' => null,
            'hire_date'     => '2002-10-01',
            'status'        => 'active',
            'department_id' => $dept->id,
            'position_id'   => $positions['ENCARREGADO (EL)'],
            'sector_id'     => $sectors['TET MT'],
        ]);
        $empMap['FUN0063'] = $emp->id;
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Electricista TET BT (AQTSE)'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Electricista de Redes BT (AQTSE)'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Electricista de Assistência à Rede e Clientes (ARC) - BT (AQTSE)'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Electricista de Assistência à Rede e Clientes (ARC) - MT (AQTSE)'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Responsável TET MT MID 30kV (AQTSE)'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['LIT (VÁLIDO)'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['TET/BT-Redes     90h/119h/120h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['TET/MT Método de Intervenção à Distância até 30KV-60h/360h/420'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Consignação de Instalações Eléctricas 18h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Execução de Juntas  em Fibras Ópticas 16h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Sistemas Solares Fotovoltaicos _COD4587 50h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Módulos Solares Fotovoltaicos _COD4588 50h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Projeto de Sistema Solar Fotovoltaico-CONSTRUÇÃO_          COD4590 50h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Projeto de Sistema Solar Fotovoltaico-INSTALAÇÃO _COD4591'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Noções de Higiene e Segurança no trabalho-Electricidade e electrónica_COD6040'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Postos de Transformação de Energia Eléctrica_COD6042'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Redes Subterrâneas AT e MT-caracterização _COD8050'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Ligação de Meios Auxiliares _COD8055'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Manutenção e Reparaç.de avarias em redes BT e IP_COD8057'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Redes Inteligentes_COD8078'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Electricidade Geral _COD0932 50h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Introdução à Gestão de Energia_COD10972'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Plataformas Elevatórias    8h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Operação de Equipamentos de Corte: Motosserras e Roçadores 12h/8h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Operadores de Grupos Electrogéneos 18h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['1ºS Socorros 12H/ 16h/24h/25h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Prevenção de Riscos Eléctricos 30h.1'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Colocação e Retirada de Paineis de Subestações, Postos de Corte e Seccionamento em Regime Especial de Exploração 7H'], 'status' => 'completed']);

        $emp = Employee::create([
            'code'          => 'FUN0064',
            'first_name'    => 'Jorge',
            'last_name'     => 'Alexandre Saraiva Marques',
            'email'         => 'jorge.alexandre.saraiva.marques@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Vila Praia de Âncora',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions['CHEFE DE EQUIPA (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0064'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0065',
            'first_name'    => 'Carlos',
            'last_name'     => 'Alberto Fernandes Gonçalves',
            'email'         => 'carlos.alberto.fernandes.gon.alves@hreminho.pt',
            'date_of_birth' => '1966-01-02',
            'nationality'   => null,
            'address'       => 'Perre',
            'work_location' => null,
            'hire_date'     => '1993-04-13',
            'status'        => 'active',
            'department_id' => $dept->id,
            'position_id'   => $positions['OFICIAL ELECTRICISTA (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0065'] = $emp->id;
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Electricista TET BT (AQTSE)'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Electricista de Redes BT (AQTSE)'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['LIT (VÁLIDO)'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['TET/BT-Redes     90h/119h/120h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Retroescav /Escavadora 8h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['FP_Giratórias-Abertura de Valas e transporte de terras 20h/25h'], 'status' => 'completed']);

        $emp = Employee::create([
            'code'          => 'FUN0069',
            'first_name'    => 'Hugo',
            'last_name'     => 'Filipe Pereira de Castro',
            'email'         => 'hugo.filipe.pereira.de.castro@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Santa Cristina, Fafe',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions[array_key_first($positions)],
            'sector_id'     => null,
        ]);
        $empMap['FUN0069'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0071',
            'first_name'    => 'Manuel',
            'last_name'     => 'Araújo Vilela',
            'email'         => 'manuel.ara.jo.vilela@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'S. Miguel, Vila Verde',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'active',
            'department_id' => $dept->id,
            'position_id'   => $positions['CHEFE DE EQUIPA (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0071'] = $emp->id;
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['TAR-Baixa Tensão 14h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Prevenção de Riscos Eléctricos 30h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Noções de Higiene e Segurança no trabalho-Electricidade e electrónica_COD6040'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Segurança Eléctrica _COD6044'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Redes Aéreas         AT e MT - caracterização _COD8048'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Redes Subterrâneas AT e MT-caracterização _COD8050'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Retroescav /Escavadora 8h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Gruas 20h/8h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['1ºS Socorros 12H/ 16h/24h/25h'], 'status' => 'completed']);

        $emp = Employee::create([
            'code'          => 'FUN0072',
            'first_name'    => 'Joaquim',
            'last_name'     => 'Manuel Ribeiro da Costa',
            'email'         => 'joaquim.manuel.ribeiro.da.costa@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Vila Verde, Vila Verde',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions[array_key_first($positions)],
            'sector_id'     => null,
        ]);
        $empMap['FUN0072'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0073',
            'first_name'    => 'Miguel',
            'last_name'     => 'Rodrigues Henriques',
            'email'         => 'miguel.rodrigues.henriques@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Moreira do Rei, Fafe',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions[array_key_first($positions)],
            'sector_id'     => null,
        ]);
        $empMap['FUN0073'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0074',
            'first_name'    => 'Armindo',
            'last_name'     => 'Lopes de Freitas',
            'email'         => 'armindo.lopes.de.freitas@hreminho.pt',
            'date_of_birth' => '1966-03-24',
            'nationality'   => null,
            'address'       => 'Arões ( São Romão)',
            'work_location' => null,
            'hire_date'     => '2000-04-10',
            'status'        => 'active',
            'department_id' => $dept->id,
            'position_id'   => $positions['OFICIAL ELECTRICISTA (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0074'] = $emp->id;
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Electricista TET BT (AQTSE)'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['LIT (VÁLIDO)'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['TET/BT-Redes     90h/119h/120h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Noções de Higiene e Segurança no trabalho-Electricidade e electrónica_COD6040'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Contagem BTN_COD8058'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Trabalhos em Tensão _COD8059'], 'status' => 'completed']);

        $emp = Employee::create([
            'code'          => 'FUN0075',
            'first_name'    => 'Agostinho',
            'last_name'     => 'Rodrigues Henriques',
            'email'         => 'agostinho.rodrigues.henriques@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Portela de Arca',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions['CHEFE DE EQUIPA (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0075'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0079',
            'first_name'    => 'José',
            'last_name'     => 'Paulo Barbosa de Abreu',
            'email'         => 'jos.paulo.barbosa.de.abreu@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Igreja',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions['OFICIAL ELECTRICISTA (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0079'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0080',
            'first_name'    => 'Carlos',
            'last_name'     => 'Manuel Fernandes Alves',
            'email'         => 'carlos.manuel.fernandes.alves@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Ribeiro',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'active',
            'department_id' => $dept->id,
            'position_id'   => $positions['CHEFE DE EQUIPA (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0080'] = $emp->id;
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['TAR 21h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Segurança Eléctrica _COD6044'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Redes Aéreas         AT e MT - caracterização _COD8048'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Redes Subterrâneas AT e MT-caracterização _COD8050'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Retroescav /Escavadora 8h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['1ºS Socorros 12H/ 16h/24h/25h'], 'status' => 'completed']);

        $emp = Employee::create([
            'code'          => 'FUN0081',
            'first_name'    => 'Sérgio',
            'last_name'     => 'André Pereira Leite',
            'email'         => 's.rgio.andr.pereira.leite@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Vila Pouca',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'active',
            'department_id' => $dept->id,
            'position_id'   => $positions['CHEFE DE EQUIPA (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0081'] = $emp->id;
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['TET/BT-Redes     90h/119h/120h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Consignação de Instalações Eléctricas 18h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Produção, Transporte e Distribuição de Energia Eléctrica_COD 5331'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Tecnologia dos Materiais Electrícos Industriais_COD 5359'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Ambiente, Segurança, Higiene e Súde no Trabalho-Conceito Básicos_COD0349'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Colocação em Serviço e Circulação Obra/Estrada-Normas_COD3915'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Postos de Transformação de Energia Eléctrica_COD6042'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Gruas 20h/8h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Plataformas Elevatórias    8h'], 'status' => 'completed']);

        $emp = Employee::create([
            'code'          => 'FUN0085',
            'first_name'    => 'Manuel',
            'last_name'     => 'Isaías Brito Pereira da Cruz',
            'email'         => 'manuel.isa.as.brito.pereira.da.cruz@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Meadela, Viana do Castelo',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'active',
            'department_id' => $dept->id,
            'position_id'   => $positions['CHEFE DE EQUIPA (CC)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0085'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0086',
            'first_name'    => 'José',
            'last_name'     => 'Henrique da Silva Pedrosa',
            'email'         => 'jos.henrique.da.silva.pedrosa@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Viana do Castelo',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions['CONDUTOR MANOBRADOR DE EQUIPAMENTOS INDUSTRIAIS (NIVEL III)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0086'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0088',
            'first_name'    => 'Manuel',
            'last_name'     => 'Rodrigues Torres',
            'email'         => 'manuel.rodrigues.torres@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Afife',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions['CONDUTOR MANOBRADOR DE EQUIPAMENTOS INDUSTRIAIS (NIVEL III)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0088'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0089',
            'first_name'    => 'Sérgio',
            'last_name'     => 'Miguel Fernandes da Costa',
            'email'         => 's.rgio.miguel.fernandes.da.costa@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Outeiro - Vila Verde',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'active',
            'department_id' => $dept->id,
            'position_id'   => $positions['CHEFE DE EQUIPA (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0089'] = $emp->id;
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['TET/BT-Redes     90h/119h/120h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['TET/MT Método de Intervenção à Distância até 30KV-60h/360h/420'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['TAR 21h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Operadores de Grupos Electrogéneos 18h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Colocação e Retirada de Paineis de Subestações, Postos de Corte e Seccionamento em Regime Especial de Exploração 7H'], 'status' => 'completed']);

        $emp = Employee::create([
            'code'          => 'FUN0090',
            'first_name'    => 'José',
            'last_name'     => 'Luís Arieira Rodrigues',
            'email'         => 'jos.lu.s.arieira.rodrigues@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Viana do Castelo',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions['CHEFE DE EQUIPA (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0090'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0091',
            'first_name'    => 'Diana',
            'last_name'     => 'Manuela de Araújo Parente Silva',
            'email'         => 'diana.manuela.de.ara.jo.parente.silva@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Viana do Castelo',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'active',
            'department_id' => $dept->id,
            'position_id'   => $positions['ESCRITURÁRIO DE 1. (ESC)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0091'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0092',
            'first_name'    => 'Filipe',
            'last_name'     => 'José Amoroso Passos Canão',
            'email'         => 'filipe.jos.amoroso.passos.can.o@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Viana do Castelo',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'active',
            'department_id' => $dept->id,
            'position_id'   => $positions['CHEFE DE DEPARTAMENTO'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0092'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0093',
            'first_name'    => 'Pedro',
            'last_name'     => 'Manuel Pinheiro Silva',
            'email'         => 'pedro.manuel.pinheiro.silva@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Atães - Vila Verde',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions['PRE OFICIAL DO 2.ANO (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0093'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0098',
            'first_name'    => 'José',
            'last_name'     => 'António Ferreira da Costa',
            'email'         => 'jos.ant.nio.ferreira.da.costa@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Santa Cristina de Arões',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'active',
            'department_id' => $dept->id,
            'position_id'   => $positions['CHEFE DE EQUIPA (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0098'] = $emp->id;
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['TET/BT-Redes     90h/119h/120h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Reciclagem TET/BT 30h/35h/46h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['TAR-Baixa Tensão 14h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Consignação de Instalações Eléctricas 18h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Contagens (BTE) 14h/18h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Montagem de Acessórios p/Redes Subterrâneas MT 36h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Noções de Higiene e Segurança no trabalho-Electricidade e electrónica_COD6040'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Postos de Transformação de Energia Eléctrica_COD6042'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Ligação de Meios Auxiliares _COD8055'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Manutenção e Reparaç.de avarias em redes BT e IP_COD8057'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Contagem BTN_COD8058'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Trabalhos em Tensão _COD8059'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Retroescav /Escavadora 8h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['1ºS Socorros 12H/ 16h/24h/25h'], 'status' => 'completed']);

        $emp = Employee::create([
            'code'          => 'FUN0100',
            'first_name'    => 'Ana',
            'last_name'     => 'Paula Felgueiras Esteves',
            'email'         => 'ana.paula.felgueiras.esteves@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Perre',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'active',
            'department_id' => $dept->id,
            'position_id'   => $positions['ESCRITURÁRIO DE 1. (ESC)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0100'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0101',
            'first_name'    => 'José',
            'last_name'     => 'Maria Martins Rodrigues',
            'email'         => 'jos.maria.martins.rodrigues@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Alvarães',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'active',
            'department_id' => $dept->id,
            'position_id'   => $positions['CHEFE DE EQUIPA (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0101'] = $emp->id;
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['TET/BT-Redes     90h/119h/120h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['TAR-Baixa Tensão 14h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Produção, Transporte e Distribuição de Energia Eléctrica_COD 5331'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Tecnologia dos Materiais Electrícos Industriais_COD 5359'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Ambiente, Segurança, Higiene e Súde no Trabalho-Conceito Básicos_COD0349'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Segurança Eléctrica _COD6044'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Redes Aéreas         AT e MT - caracterização _COD8048'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Redes Subterrâneas AT e MT-caracterização _COD8050'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Operação de Equipamentos de Corte: Motosserras e Roçadores 12h/8h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Operadores de Grupos Electrogéneos 18h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Socorrismo e Resgate do Acidentado 24h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Prevenção de Riscos Eléctricos 30h.1'], 'status' => 'completed']);

        $emp = Employee::create([
            'code'          => 'FUN0102',
            'first_name'    => 'Sandra',
            'last_name'     => 'Marisa Pires Martins de Araújo',
            'email'         => 'sandra.marisa.pires.martins.de.ara.jo@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Nogueira',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'active',
            'department_id' => $dept->id,
            'position_id'   => $positions['ESCRITURÁRIO DE 1. (ESC)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0102'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0103',
            'first_name'    => 'José',
            'last_name'     => 'Carlos Marcelo Victorino',
            'email'         => 'jos.carlos.marcelo.victorino@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Serreleis',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions['OFICIAL ELECTRICISTA (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0103'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0104',
            'first_name'    => 'Vitor',
            'last_name'     => 'Manuel Pereira Martins',
            'email'         => 'vitor.manuel.pereira.martins@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Vila',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions['OFICIAL ELECTRICISTA (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0104'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0106',
            'first_name'    => 'António',
            'last_name'     => 'Pereira Carvalhido da Silva',
            'email'         => 'ant.nio.pereira.carvalhido.da.silva@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Riba de Âncora',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions['CHEFE DE DEPARTAMENTO'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0106'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0107',
            'first_name'    => 'Orlando',
            'last_name'     => 'Felgueiras Gomes',
            'email'         => 'orlando.felgueiras.gomes@hreminho.pt',
            'date_of_birth' => '1975-11-26',
            'nationality'   => null,
            'address'       => 'Viana do Castelo',
            'work_location' => 'Viana',
            'hire_date'     => '2010-01-01',
            'status'        => 'active',
            'department_id' => $dept->id,
            'position_id'   => $positions['CHEFE DE EQUIPA (EL)'],
            'sector_id'     => $sectors['Renováveis'],
        ]);
        $empMap['FUN0107'] = $emp->id;
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Informática na Optica do Utilizador 40h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['STAC- Segurança em Trabalhos e Altura-Coberturas, Telhados e Fachadas 8h/16h/14h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Sistemas Solares Fotovoltaicos _COD4587 50h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Projeto de Sistema Solar Fotovoltaico-CONSTRUÇÃO_          COD4590 50h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Projeto de Sistema Solar Fotovoltaico-INSTALAÇÃO _COD4591'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Noções de Higiene e Segurança no trabalho-Electricidade e electrónica_COD6040'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Segurança Eléctrica _COD6044'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Redes Inteligentes_COD8078'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Electricidade Geral _COD0932 50h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Empilhadores 20h/8h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Plataformas Elevatórias    8h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Sensibilização 1ºos Socorros 7h/8h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Suporte Básico de Vida 4h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Instalações, Quadros e Redes Eléctricas e Fotovoltaicas Residenciais e Fabris de Peq. Ou Média Dimensão 16h'], 'status' => 'completed']);

        $emp = Employee::create([
            'code'          => 'FUN0109',
            'first_name'    => 'José',
            'last_name'     => 'Manuel Abreu Alves',
            'email'         => 'jos.manuel.abreu.alves@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Viana do Castelo',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions['FIEL DE ARMAZEM (COM)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0109'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0112',
            'first_name'    => 'Eurico',
            'last_name'     => 'Sousa Conceição',
            'email'         => 'eurico.sousa.concei.o@hreminho.pt',
            'date_of_birth' => '1966-06-22',
            'nationality'   => null,
            'address'       => 'Viana do Castelo',
            'work_location' => null,
            'hire_date'     => '2003-07-10',
            'status'        => 'active',
            'department_id' => $dept->id,
            'position_id'   => $positions['OFICIAL ELECTRICISTA (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0112'] = $emp->id;
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Electricista de Redes BT (AQTSE)'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['TET/BT-Redes     90h/119h/120h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Operação de Equipamentos de Corte: Motosserras e Roçadores 12h/8h'], 'status' => 'completed']);

        $emp = Employee::create([
            'code'          => 'FUN0114',
            'first_name'    => 'José',
            'last_name'     => 'Freitas Ferreira',
            'email'         => 'jos.freitas.ferreira@hreminho.pt',
            'date_of_birth' => '1974-08-21',
            'nationality'   => null,
            'address'       => 'Revelhe',
            'work_location' => null,
            'hire_date'     => '2001-09-01',
            'status'        => 'active',
            'department_id' => $dept->id,
            'position_id'   => $positions['OFICIAL ELECTRICISTA (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0114'] = $emp->id;
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Electricista TET BT (AQTSE)'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Electricista de Redes BT (AQTSE)'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['LIT (VÁLIDO)'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['TET/BT-Redes     90h/119h/120h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['INST CONT BTN_ Instalador de Contagem Baixa Tensão Normal (AQTSE)'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Contagem de Energia BTN 7h/14h/18h/21h/28h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Montagem de Acessórios p/Redes Subterrâneas MT 36h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Gruas 20h/8h'], 'status' => 'completed']);

        $emp = Employee::create([
            'code'          => 'FUN0116',
            'first_name'    => 'Raul',
            'last_name'     => 'César Pinto Miranda Lima',
            'email'         => 'raul.c.sar.pinto.miranda.lima@hreminho.pt',
            'date_of_birth' => '1972-01-23',
            'nationality'   => null,
            'address'       => 'Vila Nova de Anha',
            'work_location' => 'Viana',
            'hire_date'     => '2010-01-01',
            'status'        => 'active',
            'department_id' => $dept->id,
            'position_id'   => $positions['CHEFE DE EQUIPA (EL)'],
            'sector_id'     => $sectors['AVAC'],
        ]);
        $empMap['FUN0116'] = $emp->id;
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Informática na Optica do Utilizador 40h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['TAR 21h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Sensibilização 1ºos Socorros 7h/8h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Suporte Básico de Vida 4h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Caldeiras Murais 14h'], 'status' => 'completed']);

        $emp = Employee::create([
            'code'          => 'FUN0117',
            'first_name'    => 'José',
            'last_name'     => 'Alberto Freitas Gonçalves',
            'email'         => 'jos.alberto.freitas.gon.alves@hreminho.pt',
            'date_of_birth' => '1976-11-16',
            'nationality'   => null,
            'address'       => 'Fafe',
            'work_location' => 'Guimarães',
            'hire_date'     => '2004-08-16',
            'status'        => 'active',
            'department_id' => $dept->id,
            'position_id'   => $positions['ENCARREGADO (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0117'] = $emp->id;
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Certificado de Competências Pedagógicas (Formador)'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['e-Formador | e-Tutor 25h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Formadores de Contagem de Energia BTE/MT 28h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Formadores de Contagem de Energia BTN 21h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Sistema Integrado de Gestão QA 20h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['LIT (VÁLIDO)'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['TET/BT-Redes     90h/119h/120h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Contagem de Energia BTE/MT 56h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Electricidade_COD4573 50h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Sistemas Solares Fotovoltaicos _COD4587 50h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Módulos Solares Fotovoltaicos _COD4588 50h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Projeto de Sistema Solar Fotovoltaico-CONSTRUÇÃO_          COD4590 50h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Projeto de Sistema Solar Fotovoltaico-INSTALAÇÃO _COD4591'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Noções de Higiene e Segurança no trabalho-Electricidade e electrónica_COD6040'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Postos de Transformação de Energia Eléctrica_COD6042'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Segurança Eléctrica _COD6044'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Manutenção e Reparaç.de avarias em redes BT e IP_COD8057'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Trabalhos em Tensão _COD8059'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Electricidade Geral _COD0932 50h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Introdução à Gestão de Energia_COD10972'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Gruas 20h/8h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Plataformas Elevatórias    8h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['1ºS Socorros 12H/ 16h/24h/25h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Sensibilização 1ºos Socorros 7h/8h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Combate a Incêndio evacuação 7h'], 'status' => 'completed']);

        $emp = Employee::create([
            'code'          => 'FUN0118',
            'first_name'    => 'José',
            'last_name'     => 'Carlos da Silva Felgueiras',
            'email'         => 'jos.carlos.da.silva.felgueiras@hreminho.pt',
            'date_of_birth' => '1984-07-13',
            'nationality'   => null,
            'address'       => 'Viana do Castelo',
            'work_location' => null,
            'hire_date'     => '2001-10-01',
            'status'        => 'active',
            'department_id' => $dept->id,
            'position_id'   => $positions['OFICIAL PRINCIPAL (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0118'] = $emp->id;
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Electricista de Assistência à Rede e Clientes (ARC) - BT (AQTSE)'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Electricista de Assistência à Rede e Clientes (ARC) - MT (AQTSE)'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['LIT (VÁLIDO)'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['TET/BT-Redes     90h/119h/120h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['TET/MT Método de Intervenção à Distância até 30KV-60h/360h/420'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['TAR-Baixa Tensão 14h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Consignação de Instalações Eléctricas 18h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Plataformas Elevatórias    8h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Operadores de Grupos Electrogéneos 18h'], 'status' => 'completed']);

        $emp = Employee::create([
            'code'          => 'FUN0119',
            'first_name'    => 'João',
            'last_name'     => 'Augusto Ferreira Martins',
            'email'         => 'jo.o.augusto.ferreira.martins@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Meadela, Viana do Castelo',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions['MOTORISTA DE PESADOS (ROD)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0119'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0120',
            'first_name'    => 'Marco',
            'last_name'     => 'Bruno da Costa Martins',
            'email'         => 'marco.bruno.da.costa.martins@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Viana do Castelo',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions['OFICIAL ELECTRICISTA (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0120'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0122',
            'first_name'    => 'Jaime',
            'last_name'     => 'Viana Forte',
            'email'         => 'jaime.viana.forte@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Caminha',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions['PRE OFICIAL DO 2.ANO (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0122'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0123',
            'first_name'    => 'David',
            'last_name'     => 'Maciel Cordeiro',
            'email'         => 'david.maciel.cordeiro@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Carvoeiro',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions['OFICIAL ELECTRICISTA (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0123'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0126',
            'first_name'    => 'Alexandre',
            'last_name'     => 'António Ribeiro Costa',
            'email'         => 'alexandre.ant.nio.ribeiro.costa@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Vila Verde, Braga',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions[array_key_first($positions)],
            'sector_id'     => null,
        ]);
        $empMap['FUN0126'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0136',
            'first_name'    => 'Sérgio',
            'last_name'     => 'Manuel Abreu da Rocha',
            'email'         => 's.rgio.manuel.abreu.da.rocha@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Vila Verde, Braga',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions[array_key_first($positions)],
            'sector_id'     => null,
        ]);
        $empMap['FUN0136'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0137',
            'first_name'    => 'Daniel',
            'last_name'     => 'da Costa Pereira',
            'email'         => 'daniel.da.costa.pereira@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Vila Verde, Braga',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions[array_key_first($positions)],
            'sector_id'     => null,
        ]);
        $empMap['FUN0137'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0139',
            'first_name'    => 'José',
            'last_name'     => 'Manuel Oliveira Correia',
            'email'         => 'jos.manuel.oliveira.correia@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Vila Verde, Braga',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions[array_key_first($positions)],
            'sector_id'     => null,
        ]);
        $empMap['FUN0139'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0140',
            'first_name'    => 'Jaime',
            'last_name'     => 'Armando da C. Malheiro Gonçalves',
            'email'         => 'jaime.armando.da.c.malheiro.gon.alves@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Vila Verde, Braga',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions[array_key_first($positions)],
            'sector_id'     => null,
        ]);
        $empMap['FUN0140'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0142',
            'first_name'    => 'Luís',
            'last_name'     => 'Filipe Fernandes',
            'email'         => 'lu.s.filipe.fernandes@hreminho.pt',
            'date_of_birth' => '1973-09-13',
            'nationality'   => null,
            'address'       => 'Travassós',
            'work_location' => null,
            'hire_date'     => '2003-11-10',
            'status'        => 'active',
            'department_id' => $dept->id,
            'position_id'   => $positions['OFICIAL ELECTRICISTA (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0142'] = $emp->id;
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Electricista TET BT (AQTSE)'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Electricista de Assistência à Rede e Clientes (ARC) - BT (AQTSE)'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Electricista de Contagem BTN e Operações Comerciais (AQTSE)'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['LIT (VÁLIDO)'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['TET/BT-Redes     90h/119h/120h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Reciclagem TET/BT 30h/35h/46h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Execução de Caixas MT 60h/30h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['INST CONT BTN_ Instalador de Contagem Baixa Tensão Normal (AQTSE)'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['INST CONT BTN_ Instalador de Contagem Baixa Tensão Especial/Média Tensão (AQTSE)'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Contagens (BTE) 14h/18h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Contagem de Energia BTN 7h/14h/18h/21h/28h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Prevenção de Riscos Eléctricos 30h.1'], 'status' => 'completed']);

        $emp = Employee::create([
            'code'          => 'FUN0144',
            'first_name'    => 'Pedro',
            'last_name'     => 'Alexandre Rodrigues de Carvalho',
            'email'         => 'pedro.alexandre.rodrigues.de.carvalho@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Viana do Castelo',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions['OFICIAL ELECTRICISTA (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0144'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0149',
            'first_name'    => 'Vitor',
            'last_name'     => 'Manuel de Castro Costa',
            'email'         => 'vitor.manuel.de.castro.costa@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Cepães',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'active',
            'department_id' => $dept->id,
            'position_id'   => $positions['CHEFE DE EQUIPA (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0149'] = $emp->id;
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['TET/BT-Redes     90h/119h/120h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['TAR 21h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Módulos Solares Fotovoltaicos _COD4588 50h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Noções de Higiene e Segurança no trabalho-Electricidade e electrónica_COD6040'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Postos de Transformação de Energia Eléctrica_COD6042'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Segurança Eléctrica _COD6044'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Ligação de Meios Auxiliares _COD8055'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Manutenção e Reparaç.de avarias em redes BT e IP_COD8057'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Contagem BTN_COD8058'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Trabalhos em Tensão _COD8059'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Redes Inteligentes_COD8078'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Electricidade Geral _COD0932 50h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Gruas 20h/8h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Plataformas Elevatórias    8h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Operadores de Grupos Electrogéneos 18h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Socorrismo e Resgate do Acidentado 24h'], 'status' => 'completed']);

        $emp = Employee::create([
            'code'          => 'FUN0164',
            'first_name'    => 'Mário',
            'last_name'     => 'Samuel Leite Magalhães',
            'email'         => 'm.rio.samuel.leite.magalh.es@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Quinchães, Fafe',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions[array_key_first($positions)],
            'sector_id'     => null,
        ]);
        $empMap['FUN0164'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0166',
            'first_name'    => 'Celso',
            'last_name'     => 'Henrique Gonçalves Ribeiro',
            'email'         => 'celso.henrique.gon.alves.ribeiro@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Esposende',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions['CHEFE DE DEPARTAMENTO'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0166'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0168',
            'first_name'    => 'João',
            'last_name'     => 'António Passos da Cunha Pereira',
            'email'         => 'jo.o.ant.nio.passos.da.cunha.pereira@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Meadela, Viana do Castelo',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'active',
            'department_id' => $dept->id,
            'position_id'   => $positions['CHEFE DE EQUIPA (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0168'] = $emp->id;
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Segurança de Manutenção e Conservação de Postos de Transformação em Tensão até 30KV'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['TAR 21h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Execução de Caixas MT 60h/30h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Montagem de Acessórios p/Redes Subterrâneas MT 36h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Produção, Transporte e Distribuição de Energia Eléctrica_COD 5331'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Tecnologia dos Materiais Electrícos Industriais_COD 5359'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Ambiente, Segurança, Higiene e Súde no Trabalho-Conceito Básicos_COD0349'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Instalações Eléctricas Coletiva e recebendo Público_COD5349'], 'status' => 'completed']);

        $emp = Employee::create([
            'code'          => 'FUN0169',
            'first_name'    => 'Luis',
            'last_name'     => 'Miguel Costa Leite',
            'email'         => 'luis.miguel.costa.leite@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Golães, Fafe',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions[array_key_first($positions)],
            'sector_id'     => null,
        ]);
        $empMap['FUN0169'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0172',
            'first_name'    => 'Orlandina',
            'last_name'     => 'Maria Sobreira Gigante',
            'email'         => 'orlandina.maria.sobreira.gigante@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Outeiro',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions['CHEFE DE DEPARTAMENTO'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0172'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0174',
            'first_name'    => 'João',
            'last_name'     => 'de Sousa Neves',
            'email'         => 'jo.o.de.sousa.neves@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Covas, Vila Verde',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions[array_key_first($positions)],
            'sector_id'     => null,
        ]);
        $empMap['FUN0174'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0176',
            'first_name'    => 'Albano',
            'last_name'     => 'da Cunha Magalhães',
            'email'         => 'albano.da.cunha.magalh.es@hreminho.pt',
            'date_of_birth' => '1967-05-19',
            'nationality'   => null,
            'address'       => 'Vila Pouca',
            'work_location' => null,
            'hire_date'     => '2006-10-02',
            'status'        => 'active',
            'department_id' => $dept->id,
            'position_id'   => $positions['OFICIAL ELECTRICISTA (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0176'] = $emp->id;
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Electricista TET BT (AQTSE)'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Electricista de Assistência à Rede e Clientes (ARC) - BT (AQTSE)'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['LIT (VÁLIDO)'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['TET/BT-Redes     90h/119h/120h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['TAR-Baixa Tensão 14h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Consignação de Instalações Eléctricas 18h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Instalador de Equipamentos de Contagem em MT/BTE (EDP)'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Contagens (BTE) e Suporte Básico de Vida 25h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Colocação em Serviço e Circulação Obra/Estrada-Normas_COD3915'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Gruas 20h/8h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Plataformas Elevatórias    8h'], 'status' => 'completed']);

        $emp = Employee::create([
            'code'          => 'FUN0179',
            'first_name'    => 'Luis',
            'last_name'     => 'Miguel Araujo Martins',
            'email'         => 'luis.miguel.araujo.martins@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Pico, Vila Verde',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions[array_key_first($positions)],
            'sector_id'     => null,
        ]);
        $empMap['FUN0179'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0180',
            'first_name'    => 'Camilo',
            'last_name'     => 'de Jesus Fernandes Arieira',
            'email'         => 'camilo.de.jesus.fernandes.arieira@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Serreleis',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions['CONDUTOR MANOBRADOR DE EQUIPAMENTOS INDUSTRIAIS (NIVEL III)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0180'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0183',
            'first_name'    => 'Hélder',
            'last_name'     => 'Ricardo Costa Alves',
            'email'         => 'h.lder.ricardo.costa.alves@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Rendufe',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions['OFICIAL PRINCIPAL (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0183'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0184',
            'first_name'    => 'Mário',
            'last_name'     => 'Henrique Freitas Nogueira',
            'email'         => 'm.rio.henrique.freitas.nogueira@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Arões ( São Romão)',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions['OFICIAL ELECTRICISTA (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0184'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0187',
            'first_name'    => 'Agostinho',
            'last_name'     => 'Lopes Gomes',
            'email'         => 'agostinho.lopes.gomes@hreminho.pt',
            'date_of_birth' => '1986-10-14',
            'nationality'   => null,
            'address'       => 'Vila Pouca',
            'work_location' => null,
            'hire_date'     => '2007-09-10',
            'status'        => 'active',
            'department_id' => $dept->id,
            'position_id'   => $positions['OFICIAL ELECTRICISTA (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0187'] = $emp->id;
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Electricista TET BT (AQTSE)'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Electricista de Redes BT (AQTSE)'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Electricista de Assistência à Rede e Clientes (ARC) - BT (AQTSE)'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Electricista de Contagem BTN e Operações Comerciais (AQTSE)'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['LIT (VÁLIDO)'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['TET/BT-Redes     90h/119h/120h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Contagem de Energia BTN 7h/14h/18h/21h/28h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Colocação em Serviço e Circulação Obra/Estrada-Normas_COD3915'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Electricidade Geral _COD0932 50h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Eficiência Energética e Energia Renováveis_COD9282'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Gruas 20h/8h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Operadores de Grupos Electrogéneos 18h'], 'status' => 'completed']);

        $emp = Employee::create([
            'code'          => 'FUN0190',
            'first_name'    => 'Manuel',
            'last_name'     => 'António Antunes Ferreira',
            'email'         => 'manuel.ant.nio.antunes.ferreira@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Viana do Castelo',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'active',
            'department_id' => $dept->id,
            'position_id'   => $positions['ENCARREGADO DE ARMAZEM (COM)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0190'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0191',
            'first_name'    => 'Paulo',
            'last_name'     => 'Jorge Fernandes de Sousa',
            'email'         => 'paulo.jorge.fernandes.de.sousa@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Viana do Castelo',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'active',
            'department_id' => $dept->id,
            'position_id'   => $positions['CONDUTOR MANOBRADOR DE EQUIPAMENTOS INDUSTRIAIS (NIVEL III)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0191'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0192',
            'first_name'    => 'Ilidio',
            'last_name'     => 'Alexandre Cerqueira Araújo',
            'email'         => 'ilidio.alexandre.cerqueira.ara.jo@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Vila de Punhe',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions['OFICIAL PRINCIPAL (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0192'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0193',
            'first_name'    => 'Ricardo',
            'last_name'     => 'Jorge Rodrigues da Cruz',
            'email'         => 'ricardo.jorge.rodrigues.da.cruz@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Xisto - Alvarães',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions[array_key_first($positions)],
            'sector_id'     => null,
        ]);
        $empMap['FUN0193'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0194',
            'first_name'    => 'Henrique',
            'last_name'     => 'Benjamim Correia da Lomba',
            'email'         => 'henrique.benjamim.correia.da.lomba@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Viana do Castelo',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions['OFICIAL PRINCIPAL (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0194'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0195',
            'first_name'    => 'Cristiano',
            'last_name'     => 'Alexandre Rodrigues Amorim',
            'email'         => 'cristiano.alexandre.rodrigues.amorim@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Meadela, Viana do Castelo',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions[array_key_first($positions)],
            'sector_id'     => null,
        ]);
        $empMap['FUN0195'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0197',
            'first_name'    => 'Manuel',
            'last_name'     => 'Forte Pinto da Costa',
            'email'         => 'manuel.forte.pinto.da.costa@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Viana do Castelo',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions['OFICIAL PRINCIPAL (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0197'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0198',
            'first_name'    => 'Paulo',
            'last_name'     => 'Alexandre Rodrigues Vieira',
            'email'         => 'paulo.alexandre.rodrigues.vieira@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Vilarelho',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions['PRE OFICIAL DO 2.ANO (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0198'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0199',
            'first_name'    => 'Nuno',
            'last_name'     => 'Miguel Magalhães Costa',
            'email'         => 'nuno.miguel.magalh.es.costa@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Moreira de Rei, Fafe',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions['OFICIAL PRINCIPAL (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0199'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0200',
            'first_name'    => 'Flávio',
            'last_name'     => 'Micael Costa Fernandes',
            'email'         => 'fl.vio.micael.costa.fernandes@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Travassós, Fafe',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions[array_key_first($positions)],
            'sector_id'     => null,
        ]);
        $empMap['FUN0200'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0201',
            'first_name'    => 'Margarete',
            'last_name'     => 'Filipa Gomes da Costa',
            'email'         => 'margarete.filipa.gomes.da.costa@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Samonde, Viana do Castelo',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'active',
            'department_id' => $dept->id,
            'position_id'   => $positions['ESCRITURÁRIO DE 1. (ESC)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0201'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0204',
            'first_name'    => 'Manuel',
            'last_name'     => 'de Almeida Correia',
            'email'         => 'manuel.de.almeida.correia@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Viana do Castelo',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'active',
            'department_id' => $dept->id,
            'position_id'   => $positions['PRE OFICIAL DO 2.ANO (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0204'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0207',
            'first_name'    => 'António',
            'last_name'     => 'Jorge Simões da Cunha',
            'email'         => 'ant.nio.jorge.sim.es.da.cunha@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Viana do Castelo',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions[array_key_first($positions)],
            'sector_id'     => null,
        ]);
        $empMap['FUN0207'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0208',
            'first_name'    => 'José',
            'last_name'     => 'Manuel Fernandes Pires',
            'email'         => 'jos.manuel.fernandes.pires@hreminho.pt',
            'date_of_birth' => '1969-08-04',
            'nationality'   => null,
            'address'       => 'Gondifelos',
            'work_location' => 'Guimarães',
            'hire_date'     => '2021-05-25',
            'status'        => 'active',
            'department_id' => $dept->id,
            'position_id'   => $positions['ENCARREGADO (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0208'] = $emp->id;
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['LIT (VÁLIDO)'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Reciclagem TET/BT 30h/35h/46h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Noções de Higiene e Segurança no trabalho-Electricidade e electrónica_COD6040'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Manutenção e Reparaç.de avarias em redes BT e IP_COD8057'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Contagem BTN_COD8058'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Trabalhos em Tensão _COD8059'], 'status' => 'completed']);

        $emp = Employee::create([
            'code'          => 'FUN0210',
            'first_name'    => 'Vítor',
            'last_name'     => 'Isidro Fernandes Pires',
            'email'         => 'v.tor.isidro.fernandes.pires@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Travassós',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions['OFICIAL PRINCIPAL (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0210'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0211',
            'first_name'    => 'Manuel',
            'last_name'     => 'Jorge Vieira',
            'email'         => 'manuel.jorge.vieira@hreminho.pt',
            'date_of_birth' => '1971-03-25',
            'nationality'   => null,
            'address'       => 'Santa Cristina de Arões',
            'work_location' => null,
            'hire_date'     => '2021-05-24',
            'status'        => 'active',
            'department_id' => $dept->id,
            'position_id'   => $positions['OFICIAL ELECTRICISTA (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0211'] = $emp->id;
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Noções de Higiene e Segurança no trabalho-Electricidade e electrónica_COD6040'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Manutenção e Reparaç.de avarias em redes BT e IP_COD8057'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Contagem BTN_COD8058'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Trabalhos em Tensão _COD8059'], 'status' => 'completed']);

        $emp = Employee::create([
            'code'          => 'FUN0212',
            'first_name'    => 'Bruno',
            'last_name'     => 'Eduardo Marques Castro',
            'email'         => 'bruno.eduardo.marques.castro@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Vinhós',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'active',
            'department_id' => $dept->id,
            'position_id'   => $positions['CONDUTOR MANOBRADOR DE EQUIPAMENTOS INDUSTRIAIS (NIVEL III)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0212'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0213',
            'first_name'    => 'Carla',
            'last_name'     => 'Sofia Rocha de Brito',
            'email'         => 'carla.sofia.rocha.de.brito@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Montaria, Viana do Castelo',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions[array_key_first($positions)],
            'sector_id'     => null,
        ]);
        $empMap['FUN0213'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0215',
            'first_name'    => 'Helder',
            'last_name'     => 'da Rocha Rebelo',
            'email'         => 'helder.da.rocha.rebelo@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Fafe',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions['AJUDANTE DO 2.ANO (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0215'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0216',
            'first_name'    => 'Silvia',
            'last_name'     => 'Cátia Torres Cachada',
            'email'         => 'silvia.c.tia.torres.cachada@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Viana do Castelo',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions['CHEFE DE DEPARTAMENTO'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0216'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0218',
            'first_name'    => 'Magno',
            'last_name'     => 'Paulo Sapeta Pereira',
            'email'         => 'magno.paulo.sapeta.pereira@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Santa Maria Maior, Viana do Castelo',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions[array_key_first($positions)],
            'sector_id'     => null,
        ]);
        $empMap['FUN0218'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0220',
            'first_name'    => 'Joaquim',
            'last_name'     => 'Filipe da Costa Neves Correia',
            'email'         => 'joaquim.filipe.da.costa.neves.correia@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Vila Verde, Vila Verde',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions[array_key_first($positions)],
            'sector_id'     => null,
        ]);
        $empMap['FUN0220'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0226',
            'first_name'    => 'José',
            'last_name'     => 'Gil Correia de Carvalho',
            'email'         => 'jos.gil.correia.de.carvalho@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Cardielos',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions['OFICIAL ELECTRICISTA (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0226'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0227',
            'first_name'    => 'Evangelista',
            'last_name'     => 'de Jesus Moreira Gigante',
            'email'         => 'evangelista.de.jesus.moreira.gigante@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Outeiro',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'active',
            'department_id' => $dept->id,
            'position_id'   => $positions['PEDREIRO DE 1. (CC)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0227'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0228',
            'first_name'    => 'Paulo',
            'last_name'     => 'Moura e Silva',
            'email'         => 'paulo.moura.e.silva@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Viana do Castelo',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions['CONDUTOR MANOBRADOR DE EQUIPAMENTOS INDUSTRIAIS (NIVEL III)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0228'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0229',
            'first_name'    => 'Adolfo',
            'last_name'     => 'Manuel Costa de Carvalho',
            'email'         => 'adolfo.manuel.costa.de.carvalho@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Celeiros, Braga',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions[array_key_first($positions)],
            'sector_id'     => null,
        ]);
        $empMap['FUN0229'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0230',
            'first_name'    => 'José',
            'last_name'     => 'Genuíno da Silva Pereira',
            'email'         => 'jos.genu.no.da.silva.pereira@hreminho.pt',
            'date_of_birth' => '1977-06-17',
            'nationality'   => null,
            'address'       => 'Golães',
            'work_location' => null,
            'hire_date'     => '2008-11-13',
            'status'        => 'active',
            'department_id' => $dept->id,
            'position_id'   => $positions['OFICIAL ELECTRICISTA (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0230'] = $emp->id;
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['LIT (VÁLIDO)'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['TET/BT-Redes     90h/119h/120h'], 'status' => 'completed']);

        $emp = Employee::create([
            'code'          => 'FUN0231',
            'first_name'    => 'Joel',
            'last_name'     => 'Machado Sampaio',
            'email'         => 'joel.machado.sampaio@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Barbosa - Fafe',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'active',
            'department_id' => $dept->id,
            'position_id'   => $positions['CHEFE DE EQUIPA (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0231'] = $emp->id;
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['TET/BT-Redes     90h/119h/120h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['TET-Limpeza e Pequena Conservação em Tensão de PT 48h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['INST CONT BTN_ Instalador de Contagem Baixa Tensão Normal (AQTSE)'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Executantes em Contagem em Energia BT E MT 18h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Montagem de Acessórios p/Redes Subterrâneas MT 36h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['1ºS Socorros 12H/ 16h/24h/25h'], 'status' => 'completed']);

        $emp = Employee::create([
            'code'          => 'FUN0232',
            'first_name'    => 'Fernando',
            'last_name'     => 'Vieito Pais da Bouça',
            'email'         => 'fernando.vieito.pais.da.bou.a@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Perre',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions['PRE OFICIAL (CC)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0232'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0234',
            'first_name'    => 'Bruno',
            'last_name'     => 'da Bouça Rocha',
            'email'         => 'bruno.da.bou.a.rocha@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Montaria, Viana do Castelo',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions['PRE OFICIAL DO 2.ANO (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0234'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0237',
            'first_name'    => 'Vitor',
            'last_name'     => 'Manuel da Cunha Rodrigues',
            'email'         => 'vitor.manuel.da.cunha.rodrigues@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Meadela, Viana do Castelo',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions['OFICIAL ELECTRICISTA (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0237'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0240',
            'first_name'    => 'David',
            'last_name'     => 'Jose da Silva Cardoso Lemos',
            'email'         => 'david.jose.da.silva.cardoso.lemos@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'S. Miguel, Vila Verde',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions[array_key_first($positions)],
            'sector_id'     => null,
        ]);
        $empMap['FUN0240'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0241',
            'first_name'    => 'Bernardo',
            'last_name'     => 'Jose Martins Fernandes',
            'email'         => 'bernardo.jose.martins.fernandes@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Pedreira',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions['OFICIAL ELECTRICISTA (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0241'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0244',
            'first_name'    => 'Maksym',
            'last_name'     => 'Cholak',
            'email'         => 'maksym.cholak@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Viana do Castelo',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions['OFICIAL ELECTRICISTA (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0244'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0245',
            'first_name'    => 'Carlos',
            'last_name'     => 'Alberto Pereira Azevedo',
            'email'         => 'carlos.alberto.pereira.azevedo@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Vilela de Cima',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions['MOTORISTA DE PESADOS (ROD)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0245'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0246',
            'first_name'    => 'Nuno',
            'last_name'     => 'Ricardo Sequeira Maciel Rocha Martins',
            'email'         => 'nuno.ricardo.sequeira.maciel.rocha.martins@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Moledo, Caminha',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions[array_key_first($positions)],
            'sector_id'     => null,
        ]);
        $empMap['FUN0246'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0247',
            'first_name'    => 'Luís',
            'last_name'     => 'Miguel da Silva Lima',
            'email'         => 'lu.s.miguel.da.silva.lima@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Chafé',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'active',
            'department_id' => $dept->id,
            'position_id'   => $positions['CHEFE DE DEPARTAMENTO'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0247'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0248',
            'first_name'    => 'Miguel',
            'last_name'     => 'Canão de Sousa',
            'email'         => 'miguel.can.o.de.sousa@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Viana do Castelo',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions[array_key_first($positions)],
            'sector_id'     => null,
        ]);
        $empMap['FUN0248'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0258',
            'first_name'    => 'Jose',
            'last_name'     => 'Manuel Miranda Dantas',
            'email'         => 'jose.manuel.miranda.dantas@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Vila Franca, Viana do Castelo',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions[array_key_first($positions)],
            'sector_id'     => null,
        ]);
        $empMap['FUN0258'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0260',
            'first_name'    => 'Adelino',
            'last_name'     => 'Machado Loureiro',
            'email'         => 'adelino.machado.loureiro@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Celeirós, Braga',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions[array_key_first($positions)],
            'sector_id'     => null,
        ]);
        $empMap['FUN0260'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0261',
            'first_name'    => 'Hugo',
            'last_name'     => 'Miguel Balsa de Sousa',
            'email'         => 'hugo.miguel.balsa.de.sousa@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Viana do Castelo',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions['PRE OFICIAL DO 2.ANO (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0261'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0262',
            'first_name'    => 'Luís',
            'last_name'     => 'Duarte Pereira Nogueira',
            'email'         => 'lu.s.duarte.pereira.nogueira@hreminho.pt',
            'date_of_birth' => '1988-06-06',
            'nationality'   => null,
            'address'       => 'Ribeiros',
            'work_location' => null,
            'hire_date'     => '2010-07-20',
            'status'        => 'active',
            'department_id' => $dept->id,
            'position_id'   => $positions['OFICIAL ELECTRICISTA (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0262'] = $emp->id;
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Electricista TET BT (AQTSE)'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Electricista de Redes BT (AQTSE)'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Electricista de Contagem BTN e Operações Comerciais (AQTSE)'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['LIT (VÁLIDO)'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['TET/BT-Redes     90h/119h/120h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['TAR-Baixa Tensão 14h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['INST CONT BTN_ Instalador de Contagem Baixa Tensão Normal (AQTSE)'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Executantes em Contagem em Energia BT E MT 18h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Prevenção de Riscos Eléctricos 30h.1'], 'status' => 'completed']);

        $emp = Employee::create([
            'code'          => 'FUN0263',
            'first_name'    => 'Jorge',
            'last_name'     => 'Manuel da Silva Cunha',
            'email'         => 'jorge.manuel.da.silva.cunha@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Meadela, Viana do Castelo',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions['CHEFE DE DEPARTAMENTO'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0263'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0264',
            'first_name'    => 'Steve',
            'last_name'     => 'Gonçalves Gomes Rodrigues',
            'email'         => 'steve.gon.alves.gomes.rodrigues@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Cabeceiras de Basto',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions['OFICIAL ELECTRICISTA (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0264'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0265',
            'first_name'    => 'Cristovao',
            'last_name'     => 'Freitas Ferreira',
            'email'         => 'cristovao.freitas.ferreira@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Fafe',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions[array_key_first($positions)],
            'sector_id'     => null,
        ]);
        $empMap['FUN0265'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0266',
            'first_name'    => 'Bernardino',
            'last_name'     => 'da Mota Magalhães',
            'email'         => 'bernardino.da.mota.magalh.es@hreminho.pt',
            'date_of_birth' => '1978-05-17',
            'nationality'   => null,
            'address'       => 'Cabeceiras de Basto',
            'work_location' => null,
            'hire_date'     => '2010-02-22',
            'status'        => 'active',
            'department_id' => $dept->id,
            'position_id'   => $positions['OFICIAL ELECTRICISTA (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0266'] = $emp->id;
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Electricista TET BT (AQTSE)'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Electricista de Assistência à Rede e Clientes (ARC) - MT (AQTSE)'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Execução TET MT MID 30kV (AQTSE)'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['LIT (VÁLIDO)'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['TET/BT-Redes     90h/119h/120h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['TET/MT Método de Intervenção à Distância até 30KV-60h/360h/420'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Reciclagem TET-MT_Método de Intervenção à Distância até 30kV (Equipas Pesadas) 48h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['TAR-Baixa Tensão 14h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Montagem de Acessórios p/Redes Subterrâneas MT 36h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Execução de Redes Subterrâneas de MT-Ligações 14h/35'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Noções de Higiene e Segurança no trabalho-Electricidade e electrónica_COD6040'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Segurança Eléctrica _COD6044'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Redes Aéreas         AT e MT - caracterização _COD8048'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Redes Subterrâneas AT e MT-caracterização _COD8050'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Gruas 20h/8h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Plataformas Elevatórias    8h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Operação de Equipamentos de Corte: Motosserras e Roçadores 12h/8h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Operadores de Grupos Electrogéneos 18h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Socorrismo e Resgate do Acidentado 24h'], 'status' => 'completed']);

        $emp = Employee::create([
            'code'          => 'FUN0267',
            'first_name'    => 'João',
            'last_name'     => 'Filipe Pimenta Ribeiro',
            'email'         => 'jo.o.filipe.pimenta.ribeiro@hreminho.pt',
            'date_of_birth' => '1980-09-02',
            'nationality'   => null,
            'address'       => 'Souto',
            'work_location' => 'Guimarães',
            'hire_date'     => '2023-01-02',
            'status'        => 'active',
            'department_id' => $dept->id,
            'position_id'   => $positions['ENCARREGADO (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0267'] = $emp->id;
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Sistema Integrado de Gestão QA 20h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Planeamento e Gestão de Obra 20h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['LIT (VÁLIDO)'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['TET/MT Método de Intervenção à Distância até 30KV-60h/360h/420'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Reciclagem TET-MT_Método de Intervenção à Distância até 30kV (Equipas Pesadas) 48h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['TAR 21h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Execução de Redes Subterrâneas de MT-Ligações 14h/35'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Ferramentas ALROC 3h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Módulos Solares Fotovoltaicos _COD4588 50h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Noções de Higiene e Segurança no trabalho-Electricidade e electrónica_COD6040'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Segurança Eléctrica _COD6044'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Manutenção e Reparaç.de avarias em redes BT e IP_COD8057'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Contagem BTN_COD8058'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Redes Inteligentes_COD8078'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Electricidade Geral _COD0932 50h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['COTS-Conduzir e Operar o Trator em Segurança 50h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['1ºS Socorros 12H/ 16h/24h/25h'], 'status' => 'completed']);

        $emp = Employee::create([
            'code'          => 'FUN0268',
            'first_name'    => 'Joaquim',
            'last_name'     => 'Magalhães Teixeira',
            'email'         => 'joaquim.magalh.es.teixeira@hreminho.pt',
            'date_of_birth' => '1969-06-17',
            'nationality'   => null,
            'address'       => 'Pedraça, Cabeceiras de Basto',
            'work_location' => null,
            'hire_date'     => '2010-02-22',
            'status'        => 'active',
            'department_id' => $dept->id,
            'position_id'   => $positions['OFICIAL ELECTRICISTA (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0268'] = $emp->id;
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Electricista de Assistência à Rede e Clientes (ARC) - MT (AQTSE)'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Execução TET MT MID 30kV (AQTSE)'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Reciclagem TET-MT_Método de Intervenção à Distância até 30kV (Equipas Pesadas) 48h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['TAR 21h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Segurança Eléctrica _COD6044'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Redes Aéreas         AT e MT - caracterização _COD8048'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Redes Subterrâneas AT e MT-caracterização _COD8050'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Plataformas Elevatórias    8h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Operação de Equipamentos de Corte: Motosserras e Roçadores 12h/8h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Socorrismo e Resgate do Acidentado 24h'], 'status' => 'completed']);

        $emp = Employee::create([
            'code'          => 'FUN0269',
            'first_name'    => 'Jorge',
            'last_name'     => 'Rafael Raposo Mesquita',
            'email'         => 'jorge.rafael.raposo.mesquita@hreminho.pt',
            'date_of_birth' => '1976-10-26',
            'nationality'   => null,
            'address'       => 'Refojos',
            'work_location' => null,
            'hire_date'     => '2010-02-22',
            'status'        => 'active',
            'department_id' => $dept->id,
            'position_id'   => $positions['OFICIAL ELECTRICISTA (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0269'] = $emp->id;
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Electricista TET BT (AQTSE)'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Electricista de Assistência à Rede e Clientes (ARC) - MT (AQTSE)'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Execução TET MT MID 30kV (AQTSE)'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['LIT (VÁLIDO)'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['TET/BT-Redes     90h/119h/120h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['TET/MT Método de Intervenção à Distância até 30KV-60h/360h/420'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Reciclagem TET-MT_Método de Intervenção à Distância até 30kV (Equipas Pesadas) 48h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['TAR 21h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Segurança Eléctrica _COD6044'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Redes Aéreas         AT e MT - caracterização _COD8048'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Redes Subterrâneas AT e MT-caracterização _COD8050'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Gruas 20h/8h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Plataformas Elevatórias    8h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Operação de Equipamentos de Corte: Motosserras e Roçadores 12h/8h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Ligação de Grupos Electrogéneos 14h (EDP)'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Operadores de Grupos Electrogéneos 18h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Socorrismo e Resgate do Acidentado 24h'], 'status' => 'completed']);

        $emp = Employee::create([
            'code'          => 'FUN0270',
            'first_name'    => 'Vitor',
            'last_name'     => 'Manuel Teixeira Badim',
            'email'         => 'vitor.manuel.teixeira.badim@hreminho.pt',
            'date_of_birth' => '1974-05-24',
            'nationality'   => null,
            'address'       => 'Cabeceiras de Basto',
            'work_location' => 'Guimarães',
            'hire_date'     => '2010-02-22',
            'status'        => 'active',
            'department_id' => $dept->id,
            'position_id'   => $positions['ENCARREGADO (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0270'] = $emp->id;
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['LIT (VÁLIDO)'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['TET/BT-Redes     90h/119h/120h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['TET/MT Método de Intervenção à Distância até 30KV-60h/360h/420'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Reciclagem TET-MT_Método de Intervenção à Distância até 30kV (Equipas Pesadas) 48h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['TAR 21h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Segurança Eléctrica _COD6044'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Redes Aéreas         AT e MT - caracterização _COD8048'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Redes Subterrâneas AT e MT-caracterização _COD8050'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Ligação de Meios Auxiliares _COD8055'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Gruas 20h/8h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Operadores de Grupos Electrogéneos 18h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Socorrismo e Resgate do Acidentado 24h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Colocação e Retirada de Paineis de Subestações, Postos de Corte e Seccionamento em Regime Especial de Exploração 7H'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Execução em Fibras Ópticas 16h'], 'status' => 'completed']);

        $emp = Employee::create([
            'code'          => 'FUN0271',
            'first_name'    => 'Helder',
            'last_name'     => 'Filipe de Castro Branco',
            'email'         => 'helder.filipe.de.castro.branco@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Cabeceiras de Basto',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'active',
            'department_id' => $dept->id,
            'position_id'   => $positions['CHEFE DE EQUIPA (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0271'] = $emp->id;
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['TET/BT-Redes     90h/119h/120h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['TET/MT Método de Intervenção à Distância até 30KV-60h/360h/420'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['TAR 21h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Plataformas Elevatórias    8h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Operadores de Grupos Electrogéneos 18h'], 'status' => 'completed']);

        $emp = Employee::create([
            'code'          => 'FUN0272',
            'first_name'    => 'Sérgio',
            'last_name'     => 'Antunes Ribeiro',
            'email'         => 's.rgio.antunes.ribeiro@hreminho.pt',
            'date_of_birth' => '1979-07-05',
            'nationality'   => null,
            'address'       => 'Fafe',
            'work_location' => 'Guimarães',
            'hire_date'     => '2010-01-25',
            'status'        => 'active',
            'department_id' => $dept->id,
            'position_id'   => $positions['ENCARREGADO (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0272'] = $emp->id;
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Informática na Optica do Utilizador 40h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['LIT (VÁLIDO)'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['TET/BT-Redes     90h/119h/120h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['INST CONT BTN_ Instalador de Contagem Baixa Tensão Normal (AQTSE)'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['CAD-Projeto de Esquemas Eléctricos_COD6771'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Sistemas Solares Fotovoltaicos _COD4587 50h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Módulos Solares Fotovoltaicos _COD4588 50h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Projeto de Sistema Solar Fotovoltaico-CONSTRUÇÃO_          COD4590 50h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Projeto de Sistema Solar Fotovoltaico-INSTALAÇÃO _COD4591'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Noções de Higiene e Segurança no trabalho-Electricidade e electrónica_COD6040'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Postos de Transformação de Energia Eléctrica_COD6042'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Ligação de Meios Auxiliares _COD8055'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Manutenção e Reparaç.de avarias em redes BT e IP_COD8057'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Trabalhos em Tensão _COD8059'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Redes Inteligentes_COD8078'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Electricidade Geral _COD0932 50h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Introdução à Gestão de Energia_COD10972'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['1ºS Socorros 12H/ 16h/24h/25h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Prevenção de Riscos Eléctricos 30h.1'], 'status' => 'completed']);

        $emp = Employee::create([
            'code'          => 'FUN0273',
            'first_name'    => 'Jorge',
            'last_name'     => 'Alberto Pereira do Couto',
            'email'         => 'jorge.alberto.pereira.do.couto@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Vila Franca, Viana do Castelo',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions['PRE OFICIAL DO 2.ANO (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0273'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0274',
            'first_name'    => 'Pedro',
            'last_name'     => 'Dário da Silva Fernandes',
            'email'         => 'pedro.d.rio.da.silva.fernandes@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Darque, Viana do Castelo',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions[array_key_first($positions)],
            'sector_id'     => null,
        ]);
        $empMap['FUN0274'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0275',
            'first_name'    => 'José',
            'last_name'     => 'Domingos Rocha Parente',
            'email'         => 'jos.domingos.rocha.parente@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Nogueira',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'active',
            'department_id' => $dept->id,
            'position_id'   => $positions['ENCARREGADO DE 1. (CC)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0275'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0276',
            'first_name'    => 'Antonio',
            'last_name'     => 'Jose Borlido Costa Parente',
            'email'         => 'antonio.jose.borlido.costa.parente@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Santa Marta de Portuzelo, Viana do Castelo',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions['OFICIAL ELECTRICISTA (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0276'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0277',
            'first_name'    => 'Renato',
            'last_name'     => 'Rodrigues Marques',
            'email'         => 'renato.rodrigues.marques@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Marinhão',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions['OFICIAL ELECTRICISTA (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0277'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0279',
            'first_name'    => 'Luis',
            'last_name'     => 'Carlos Pereira Teixeira',
            'email'         => 'luis.carlos.pereira.teixeira@hreminho.pt',
            'date_of_birth' => '1985-10-04',
            'nationality'   => null,
            'address'       => 'Moreira do Rei, Fafe',
            'work_location' => null,
            'hire_date'     => '2025-02-03',
            'status'        => 'active',
            'department_id' => $dept->id,
            'position_id'   => $positions['OFICIAL ELECTRICISTA (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0279'] = $emp->id;
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Electricista TET BT (AQTSE)'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Electricista de Contagem BTN e Operações Comerciais (AQTSE)'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['LIT (VÁLIDO)'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['TET/BT-Redes     90h/119h/120h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Contagens (BTE) e Suporte Básico de Vida 25h'], 'status' => 'completed']);

        $emp = Employee::create([
            'code'          => 'FUN0280',
            'first_name'    => 'Vitor',
            'last_name'     => 'Manuel Cerqueira Araujo',
            'email'         => 'vitor.manuel.cerqueira.araujo@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Vila Verde, Vila Verde',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions[array_key_first($positions)],
            'sector_id'     => null,
        ]);
        $empMap['FUN0280'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0281',
            'first_name'    => 'José',
            'last_name'     => 'Pedro de Sousa Ferreira',
            'email'         => 'jos.pedro.de.sousa.ferreira@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Viana do Castelo',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions[array_key_first($positions)],
            'sector_id'     => null,
        ]);
        $empMap['FUN0281'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0282',
            'first_name'    => 'César',
            'last_name'     => 'Manuel da Silva Fernandes',
            'email'         => 'c.sar.manuel.da.silva.fernandes@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Fafe',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions[array_key_first($positions)],
            'sector_id'     => null,
        ]);
        $empMap['FUN0282'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0283',
            'first_name'    => 'Pedro',
            'last_name'     => 'Manuel Branco Costa',
            'email'         => 'pedro.manuel.branco.costa@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Viana do Castelo',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions['CHEFE DE DEPARTAMENTO'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0283'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0284',
            'first_name'    => 'Paulo',
            'last_name'     => 'Americo Vieira Pires',
            'email'         => 'paulo.americo.vieira.pires@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Viana do Castelo',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions['CHEFE DE EQUIPA (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0284'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0285',
            'first_name'    => 'Filipe',
            'last_name'     => 'Sousa Carvalho',
            'email'         => 'filipe.sousa.carvalho@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Mujães',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'active',
            'department_id' => $dept->id,
            'position_id'   => $positions['CHEFE DE DEPARTAMENTO'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0285'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0286',
            'first_name'    => 'José',
            'last_name'     => 'António Sousa Oliveira',
            'email'         => 'jos.ant.nio.sousa.oliveira@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Portela de Vade',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'active',
            'department_id' => $dept->id,
            'position_id'   => $positions['FIEL DE ARMAZEM (COM)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0286'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0287',
            'first_name'    => 'Américo',
            'last_name'     => 'da Silva Franco',
            'email'         => 'am.rico.da.silva.franco@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Fontão',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'active',
            'department_id' => $dept->id,
            'position_id'   => $positions['MECÂNICO DE AUTOMÓVEIS DE 1. (MET)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0287'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0288',
            'first_name'    => 'Carlos',
            'last_name'     => 'Duarte Parente de Amorim',
            'email'         => 'carlos.duarte.parente.de.amorim@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Meadela, Viana do Castelo',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions[array_key_first($positions)],
            'sector_id'     => null,
        ]);
        $empMap['FUN0288'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0289',
            'first_name'    => 'Carlos',
            'last_name'     => 'Alexandre Monteiro Esteves',
            'email'         => 'carlos.alexandre.monteiro.esteves@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Areosa, Viana do Castelo',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions[array_key_first($positions)],
            'sector_id'     => null,
        ]);
        $empMap['FUN0289'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0290',
            'first_name'    => 'Tiago',
            'last_name'     => 'Mendes Freitas',
            'email'         => 'tiago.mendes.freitas@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'São Clemente de Silvares, Fafe',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions[array_key_first($positions)],
            'sector_id'     => null,
        ]);
        $empMap['FUN0290'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0291',
            'first_name'    => 'Tiago',
            'last_name'     => 'Silva Teixeira',
            'email'         => 'tiago.silva.teixeira@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Fafe',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'active',
            'department_id' => $dept->id,
            'position_id'   => $positions['CHEFE DE EQUIPA (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0291'] = $emp->id;
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['TET/BT-Redes     90h/119h/120h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Reciclagem TET/BT 30h/35h/46h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['INST CONT BTN_ Instalador de Contagem Baixa Tensão Normal (AQTSE)'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Contagem de Energia BTN 7h/14h/18h/21h/28h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Socorrismo e Resgate do Acidentado 24h'], 'status' => 'completed']);

        $emp = Employee::create([
            'code'          => 'FUN0292',
            'first_name'    => 'Carlos',
            'last_name'     => 'Frederico Castro Cunha',
            'email'         => 'carlos.frederico.castro.cunha@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Travassós, Fafe',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions[array_key_first($positions)],
            'sector_id'     => null,
        ]);
        $empMap['FUN0292'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0293',
            'first_name'    => 'Luis',
            'last_name'     => 'Miguel Castro Fernandes',
            'email'         => 'luis.miguel.castro.fernandes@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'São Clemente de Silvares, Fafe',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions[array_key_first($positions)],
            'sector_id'     => null,
        ]);
        $empMap['FUN0293'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0294',
            'first_name'    => 'Márcio',
            'last_name'     => 'Alexandre Barreto Dias',
            'email'         => 'm.rcio.alexandre.barreto.dias@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Alvarães, Viana do Castelo',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions[array_key_first($positions)],
            'sector_id'     => null,
        ]);
        $empMap['FUN0294'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0295',
            'first_name'    => 'Daniel',
            'last_name'     => 'Costa Castro',
            'email'         => 'daniel.costa.castro@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Fafe',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions[array_key_first($positions)],
            'sector_id'     => null,
        ]);
        $empMap['FUN0295'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0296',
            'first_name'    => 'Cristiano',
            'last_name'     => 'Miguel Ferreira Henriques',
            'email'         => 'cristiano.miguel.ferreira.henriques@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Moreira do Rei, Fafe',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions[array_key_first($positions)],
            'sector_id'     => null,
        ]);
        $empMap['FUN0296'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0297',
            'first_name'    => 'José',
            'last_name'     => 'Alberto Martins Teixeira',
            'email'         => 'jos.alberto.martins.teixeira@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Vila Verde, Braga',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions[array_key_first($positions)],
            'sector_id'     => null,
        ]);
        $empMap['FUN0297'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0298',
            'first_name'    => 'Hugo',
            'last_name'     => 'Santos Costa da Cunha',
            'email'         => 'hugo.santos.costa.da.cunha@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Viana do Castelo',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions[array_key_first($positions)],
            'sector_id'     => null,
        ]);
        $empMap['FUN0298'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0299',
            'first_name'    => 'Aerlon',
            'last_name'     => 'Elieser Silveira',
            'email'         => 'aerlon.elieser.silveira@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'V N Famalicão',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'active',
            'department_id' => $dept->id,
            'position_id'   => $positions['CHEFE DE EQUIPA (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0299'] = $emp->id;
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['TET/BT-Redes     90h/119h/120h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Reciclagem TET/BT 30h/35h/46h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['TAR-Baixa Tensão 14h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Contagens (BTE) 14h/18h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Contagem de Energia BTN 7h/14h/18h/21h/28h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Electricidade Geral _COD0932 50h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Eficiência Energética e Energia Renováveis_COD9282'], 'status' => 'completed']);

        $emp = Employee::create([
            'code'          => 'FUN0300',
            'first_name'    => 'Romeu',
            'last_name'     => 'Filipe Castro Costa',
            'email'         => 'romeu.filipe.castro.costa@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Fafe',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions['CHEFE DE EQUIPA (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0300'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0301',
            'first_name'    => 'Manuel',
            'last_name'     => 'Joaquim Oliveira Magalhães',
            'email'         => 'manuel.joaquim.oliveira.magalh.es@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Cabeceira de Basto',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions['OFICIAL ELECTRICISTA (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0301'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0302',
            'first_name'    => 'Joaquim',
            'last_name'     => 'Lopes Gonçalves',
            'email'         => 'joaquim.lopes.gon.alves@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Travassós',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'active',
            'department_id' => $dept->id,
            'position_id'   => $positions['CHEFE DE EQUIPA (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0302'] = $emp->id;
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['TET/BT-Redes     90h/119h/120h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['INST CONT BTN_ Instalador de Contagem Baixa Tensão Normal (AQTSE)'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Contagem de Energia BTN 7h/14h/18h/21h/28h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Executantes em Contagem em Energia BT E MT 18h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Noções de Higiene e Segurança no trabalho-Electricidade e electrónica_COD6040'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Manutenção e Reparaç.de avarias em redes BT e IP_COD8057'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Contagem BTN_COD8058'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Trabalhos em Tensão _COD8059'], 'status' => 'completed']);

        $emp = Employee::create([
            'code'          => 'FUN0303',
            'first_name'    => 'Aristides',
            'last_name'     => 'Rodrigo da Costa Sousa',
            'email'         => 'aristides.rodrigo.da.costa.sousa@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Alvarães, Viana do Castelo',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions[array_key_first($positions)],
            'sector_id'     => null,
        ]);
        $empMap['FUN0303'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0304',
            'first_name'    => 'Albano',
            'last_name'     => 'Ribeiro Costa',
            'email'         => 'albano.ribeiro.costa@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Vinhós, Fafe',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'active',
            'department_id' => $dept->id,
            'position_id'   => $positions['OFICIAL ELECTRICISTA (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0304'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0305',
            'first_name'    => 'Romeu',
            'last_name'     => 'Maia Lapeira',
            'email'         => 'romeu.maia.lapeira@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Fafe',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions[array_key_first($positions)],
            'sector_id'     => null,
        ]);
        $empMap['FUN0305'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0306',
            'first_name'    => 'Manuel',
            'last_name'     => 'Rodrigues Ribeiro',
            'email'         => 'manuel.rodrigues.ribeiro@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Vila de Punhe, Viana do Castelo',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions[array_key_first($positions)],
            'sector_id'     => null,
        ]);
        $empMap['FUN0306'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0307',
            'first_name'    => 'André',
            'last_name'     => 'Cadilha Filgueiras',
            'email'         => 'andr.cadilha.filgueiras@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Viana do Castelo',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions[array_key_first($positions)],
            'sector_id'     => null,
        ]);
        $empMap['FUN0307'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0308',
            'first_name'    => 'Luís',
            'last_name'     => 'Filipe de Almeida Castro',
            'email'         => 'lu.s.filipe.de.almeida.castro@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Viana do Castelo',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions['PRE OFICIAL DO 2.ANO (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0308'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0309',
            'first_name'    => 'Victor',
            'last_name'     => 'Michael Hassan',
            'email'         => 'victor.michael.hassan@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Monserrate, Viana do Castelo',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions[array_key_first($positions)],
            'sector_id'     => null,
        ]);
        $empMap['FUN0309'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0310',
            'first_name'    => 'Bruno',
            'last_name'     => 'Daniel Marinho Seixas',
            'email'         => 'bruno.daniel.marinho.seixas@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Fridão',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions['CONDUTOR MANOBRADOR DE EQUIPAMENTOS INDUSTRIAIS (NIVEL III)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0310'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0311',
            'first_name'    => 'José',
            'last_name'     => 'Rafael Alves da Costa',
            'email'         => 'jos.rafael.alves.da.costa@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Cisão',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'active',
            'department_id' => $dept->id,
            'position_id'   => $positions['CONDUTOR MANOBRADOR DE EQUIPAMENTOS INDUSTRIAIS (NIVEL III)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0311'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0312',
            'first_name'    => 'José',
            'last_name'     => 'Pedro Teixeira Leite',
            'email'         => 'jos.pedro.teixeira.leite@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Refojos, Cabeceiras de Basto',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions['OFICIAL ELECTRICISTA (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0312'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0313',
            'first_name'    => 'Carlos',
            'last_name'     => 'Manuel Castro Branco',
            'email'         => 'carlos.manuel.castro.branco@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Refojos',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'active',
            'department_id' => $dept->id,
            'position_id'   => $positions['CHEFE DE EQUIPA (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0313'] = $emp->id;
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['TET/MT Método de Intervenção à Distância até 30KV-60h/360h/420'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['TAR 21h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Montagem de Acessórios p/Redes Subterrâneas MT 36h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Execução de Redes Subterrâneas de MT-Ligações 14h/35'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Segurança Eléctrica _COD6044'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Redes Aéreas         AT e MT - caracterização _COD8048'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Redes Subterrâneas AT e MT-caracterização _COD8050'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Operadores de Grupos Electrogéneos 18h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Execução em Fibras Ópticas 16h'], 'status' => 'completed']);

        $emp = Employee::create([
            'code'          => 'FUN0314',
            'first_name'    => 'Paulo',
            'last_name'     => 'José dos Santos Magalhães',
            'email'         => 'paulo.jos.dos.santos.magalh.es@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Cabeceiras de Basto',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'active',
            'department_id' => $dept->id,
            'position_id'   => $positions['CHEFE DE EQUIPA (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0314'] = $emp->id;
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Módulos Solares Fotovoltaicos _COD4588 50h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Noções de Higiene e Segurança no trabalho-Electricidade e electrónica_COD6040'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Segurança Eléctrica _COD6044'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Contagem BTN_COD8058'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Redes Inteligentes_COD8078'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Electricidade Geral _COD0932 50h'], 'status' => 'completed']);

        $emp = Employee::create([
            'code'          => 'FUN0315',
            'first_name'    => 'Vitor',
            'last_name'     => 'Manuel Araújo Cerqueira',
            'email'         => 'vitor.manuel.ara.jo.cerqueira@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Portela de Vade - Atães, Vila Verde',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions['CONDUTOR MANOBRADOR DE EQUUIPAMENTOS INDUSTRIAIS (NIVEL II)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0315'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0316',
            'first_name'    => 'Pedro',
            'last_name'     => 'Joel Peixoto Novais',
            'email'         => 'pedro.joel.peixoto.novais@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Estorãos',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'active',
            'department_id' => $dept->id,
            'position_id'   => $positions['OFICIAL ELECTRICISTA (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0316'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0317',
            'first_name'    => 'Anthony',
            'last_name'     => 'Magalhães Fernandes',
            'email'         => 'anthony.magalh.es.fernandes@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Travassós, Fafe',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions[array_key_first($positions)],
            'sector_id'     => null,
        ]);
        $empMap['FUN0317'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0318',
            'first_name'    => 'Humberto',
            'last_name'     => 'Diogo Barroso Ramos',
            'email'         => 'humberto.diogo.barroso.ramos@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Pedraça, Cabeceiras de Basto',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions[array_key_first($positions)],
            'sector_id'     => null,
        ]);
        $empMap['FUN0318'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0319',
            'first_name'    => 'Carlos',
            'last_name'     => 'Daniel Pimenta Antunes',
            'email'         => 'carlos.daniel.pimenta.antunes@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Barros, Vila Verde',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions[array_key_first($positions)],
            'sector_id'     => null,
        ]);
        $empMap['FUN0319'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0320',
            'first_name'    => 'Humberto',
            'last_name'     => 'Filipe Magalhães Alves',
            'email'         => 'humberto.filipe.magalh.es.alves@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Fafe',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions['PRE OFICIAL DO 2.ANO (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0320'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0321',
            'first_name'    => 'Manuel',
            'last_name'     => 'Fernandes Pereira',
            'email'         => 'manuel.fernandes.pereira@hreminho.pt',
            'date_of_birth' => '1963-08-17',
            'nationality'   => null,
            'address'       => 'Vinhós',
            'work_location' => null,
            'hire_date'     => '2026-04-13',
            'status'        => 'active',
            'department_id' => $dept->id,
            'position_id'   => $positions['ENCARREGADO (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0321'] = $emp->id;
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Electricista TET BT (AQTSE)'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Electricista de Redes BT (AQTSE)'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['TET/BT-Redes     90h/119h/120h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['TAR-Baixa Tensão 14h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Noções de Higiene e Segurança no trabalho-Electricidade e electrónica_COD6040'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Manutenção e Reparaç.de avarias em redes BT e IP_COD8057'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Contagem BTN_COD8058'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Trabalhos em Tensão _COD8059'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Electricidade Geral _COD0932 50h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Eficiência Energética e Energia Renováveis_COD9282'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Gruas 20h/8h'], 'status' => 'completed']);

        $emp = Employee::create([
            'code'          => 'FUN0322',
            'first_name'    => 'Benjamim',
            'last_name'     => 'Cândido Freitas Gonçalves',
            'email'         => 'benjamim.c.ndido.freitas.gon.alves@hreminho.pt',
            'date_of_birth' => '1975-11-11',
            'nationality'   => null,
            'address'       => 'Quinchães - Fafe',
            'work_location' => null,
            'hire_date'     => '2016-04-04',
            'status'        => 'active',
            'department_id' => $dept->id,
            'position_id'   => $positions['OFICIAL PRINCIPAL (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0322'] = $emp->id;
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['LIT (VÁLIDO)'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['TAR-Baixa Tensão 14h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['INST CONT BTN_ Instalador de Contagem Baixa Tensão Normal (AQTSE)'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Contagem de Energia BTN 7h/14h/18h/21h/28h'], 'status' => 'completed']);

        $emp = Employee::create([
            'code'          => 'FUN0323',
            'first_name'    => 'Anibal',
            'last_name'     => 'de Magalhães Leite',
            'email'         => 'anibal.de.magalh.es.leite@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Refojos - Cabeceiras de Basto',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions['OFICIAL ELECTRICISTA (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0323'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0324',
            'first_name'    => 'Nuno',
            'last_name'     => 'Andre Marinho Carvalho',
            'email'         => 'nuno.andre.marinho.carvalho@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Cepães - Fafe',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions[array_key_first($positions)],
            'sector_id'     => null,
        ]);
        $empMap['FUN0324'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0325',
            'first_name'    => 'João',
            'last_name'     => 'Mesquita Marinho',
            'email'         => 'jo.o.mesquita.marinho@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Arnoia Celorico de Basto',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'active',
            'department_id' => $dept->id,
            'position_id'   => $positions['SERRALHEIRO CIVIL DE 1. (MET)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0325'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0326',
            'first_name'    => 'Eugénio',
            'last_name'     => 'Miranda Rodrigues',
            'email'         => 'eug.nio.miranda.rodrigues@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Barroselas',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions['MOTORISTA DE PESADOS (ROD)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0326'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0327',
            'first_name'    => 'Pedro',
            'last_name'     => 'Manuel da Cruz Soares',
            'email'         => 'pedro.manuel.da.cruz.soares@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Meadela',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions[array_key_first($positions)],
            'sector_id'     => null,
        ]);
        $empMap['FUN0327'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0328',
            'first_name'    => 'Miguel',
            'last_name'     => 'Carvalho Videira',
            'email'         => 'miguel.carvalho.videira@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Meadela',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions[array_key_first($positions)],
            'sector_id'     => null,
        ]);
        $empMap['FUN0328'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0329',
            'first_name'    => 'José',
            'last_name'     => 'Manuel Amorim de Sousa',
            'email'         => 'jos.manuel.amorim.de.sousa@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Vila Verde',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions[array_key_first($positions)],
            'sector_id'     => null,
        ]);
        $empMap['FUN0329'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0330',
            'first_name'    => 'Bruno',
            'last_name'     => 'Alexandre Mendes Macedo',
            'email'         => 'bruno.alexandre.mendes.macedo@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Vizela',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'active',
            'department_id' => $dept->id,
            'position_id'   => $positions['CHEFE DE EQUIPA (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0330'] = $emp->id;
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['TET/BT-Redes     90h/119h/120h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['TAR-Baixa Tensão 14h'], 'status' => 'completed']);

        $emp = Employee::create([
            'code'          => 'FUN0331',
            'first_name'    => 'Manuel',
            'last_name'     => 'Fernando Mendes Batista',
            'email'         => 'manuel.fernando.mendes.batista@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Armil - Fafe',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions['OFICIAL ELECTRICISTA (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0331'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0332',
            'first_name'    => 'Carlos',
            'last_name'     => 'Davide Freitas Barroso',
            'email'         => 'carlos.davide.freitas.barroso@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Travassós',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions['OFICIAL ELECTRICISTA (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0332'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0333',
            'first_name'    => 'FLAVIO',
            'last_name'     => 'DIOGO ALVES MAGALHÃES',
            'email'         => 'flavio.diogo.alves.magalh.es@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Vila Verde',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions[array_key_first($positions)],
            'sector_id'     => null,
        ]);
        $empMap['FUN0333'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0334',
            'first_name'    => 'José',
            'last_name'     => 'Charles Casimiro Rodrigues Vaz',
            'email'         => 'jos.charles.casimiro.rodrigues.vaz@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Ancora',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions[array_key_first($positions)],
            'sector_id'     => null,
        ]);
        $empMap['FUN0334'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0335',
            'first_name'    => 'Rui',
            'last_name'     => 'Alberto da Costa Neves Correia',
            'email'         => 'rui.alberto.da.costa.neves.correia@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Vila Verde',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions[array_key_first($positions)],
            'sector_id'     => null,
        ]);
        $empMap['FUN0335'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0336',
            'first_name'    => 'José',
            'last_name'     => 'Fernando Teixeira Pereira',
            'email'         => 'jos.fernando.teixeira.pereira@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Refojos - Cabeceiras de Basto',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions[array_key_first($positions)],
            'sector_id'     => null,
        ]);
        $empMap['FUN0336'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0337',
            'first_name'    => 'Ivo',
            'last_name'     => 'André Canão de Carvalho',
            'email'         => 'ivo.andr.can.o.de.carvalho@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Mazarefes',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'active',
            'department_id' => $dept->id,
            'position_id'   => $positions['CHEFE DE DEPARTAMENTO'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0337'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0338',
            'first_name'    => 'Manuel',
            'last_name'     => 'Henrique de Sousa',
            'email'         => 'manuel.henrique.de.sousa@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Aboim de Nóbrega - Vila Verde',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions[array_key_first($positions)],
            'sector_id'     => null,
        ]);
        $empMap['FUN0338'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0339',
            'first_name'    => 'Joaquim',
            'last_name'     => 'Augusto Cruz Carvalho',
            'email'         => 'joaquim.augusto.cruz.carvalho@hreminho.pt',
            'date_of_birth' => '1983-01-05',
            'nationality'   => null,
            'address'       => 'Viana do Castelo (Darque)',
            'work_location' => 'Viana',
            'hire_date'     => '2010-01-01',
            'status'        => 'active',
            'department_id' => $dept->id,
            'position_id'   => $positions['PRE OFICIAL DO 2.ANO (EL)'],
            'sector_id'     => $sectors['AVAC'],
        ]);
        $empMap['FUN0339'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0340',
            'first_name'    => 'Pedro',
            'last_name'     => 'Miguel Vieira Brandão',
            'email'         => 'pedro.miguel.vieira.brand.o@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Cardielos',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions[array_key_first($positions)],
            'sector_id'     => null,
        ]);
        $empMap['FUN0340'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0341',
            'first_name'    => 'Eduardo',
            'last_name'     => 'Miguel Ferreira Meira',
            'email'         => 'eduardo.miguel.ferreira.meira@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Monserrate',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions[array_key_first($positions)],
            'sector_id'     => null,
        ]);
        $empMap['FUN0341'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0342',
            'first_name'    => 'Agostinho',
            'last_name'     => 'Marques de Araújo',
            'email'         => 'agostinho.marques.de.ara.jo@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Outeiro - Ribeira',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'active',
            'department_id' => $dept->id,
            'position_id'   => $positions['OFICIAL ELECTRICISTA (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0342'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0343',
            'first_name'    => 'Ruben',
            'last_name'     => 'André Pires Gonçalves',
            'email'         => 'ruben.andr.pires.gon.alves@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Quinchães',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions['PRE OFICIAL DO 2.ANO (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0343'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0344',
            'first_name'    => 'Helder',
            'last_name'     => 'Henrique Cunha Costa',
            'email'         => 'helder.henrique.cunha.costa@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Viana do Castelo',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions['SERRALHEIRO CIVIL DE 1. (MET)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0344'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0345',
            'first_name'    => 'Ricardo',
            'last_name'     => 'Daniel Silva Salgado',
            'email'         => 'ricardo.daniel.silva.salgado@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Guimarães',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'active',
            'department_id' => $dept->id,
            'position_id'   => $positions['ENCARREGADO DE ARMAZEM (COM)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0345'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0346',
            'first_name'    => 'Paulo',
            'last_name'     => 'Jorge Novais da Mota',
            'email'         => 'paulo.jorge.novais.da.mota@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Cabeceiras de Basto',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions['AJUDANTE DO 2.ANO (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0346'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0347',
            'first_name'    => 'Ariana',
            'last_name'     => 'Augusta Teixeira Oliveira e Silva',
            'email'         => 'ariana.augusta.teixeira.oliveira.e.silva@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'S. João de Ponte - Guimarães',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions['ESCRITURÁRIO DE 3. (ESC)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0347'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0348',
            'first_name'    => 'António',
            'last_name'     => 'Lima de Sousa Basto',
            'email'         => 'ant.nio.lima.de.sousa.basto@hreminho.pt',
            'date_of_birth' => '1967-08-20',
            'nationality'   => null,
            'address'       => 'Viana do Castelo',
            'work_location' => 'Viana',
            'hire_date'     => '2012-11-08',
            'status'        => 'active',
            'department_id' => $dept->id,
            'position_id'   => $positions['ENCARREGADO (EL)'],
            'sector_id'     => $sectors['AVAC'],
        ]);
        $empMap['FUN0348'] = $emp->id;
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['TAR-Baixa Tensão 14h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Empilhadores 20h/8h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Sensibilização 1ºos Socorros 7h/8h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Suporte Básico de Vida 4h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Higiene e Segurança no Trabalho (MetSep)'], 'status' => 'completed']);

        $emp = Employee::create([
            'code'          => 'FUN0349',
            'first_name'    => 'David',
            'last_name'     => 'de Freitas Novais',
            'email'         => 'david.de.freitas.novais@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Fafe',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions['PRE OFICIAL DO 2.ANO (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0349'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0350',
            'first_name'    => 'Daniel',
            'last_name'     => 'Almeida Andrade',
            'email'         => 'daniel.almeida.andrade@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Oliveira São Mateus - V.N. Famalicão',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions[array_key_first($positions)],
            'sector_id'     => null,
        ]);
        $empMap['FUN0350'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0351',
            'first_name'    => 'Carlos',
            'last_name'     => 'Manuel Manso Pontes',
            'email'         => 'carlos.manuel.manso.pontes@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Perre',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions['OFICIAL ELECTRICISTA (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0351'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0352',
            'first_name'    => 'Kévin',
            'last_name'     => 'Taveira',
            'email'         => 'k.vin.taveira@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Serreleis',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions['PRE OFICIAL DO 2.ANO (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0352'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0353',
            'first_name'    => 'Joaquim',
            'last_name'     => 'Bento Teixeira de Magalhães',
            'email'         => 'joaquim.bento.teixeira.de.magalh.es@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Arnoia - Celorico de Basto',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'active',
            'department_id' => $dept->id,
            'position_id'   => $positions['MOTORISTA DE PESADOS (ROD)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0353'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0354',
            'first_name'    => 'Bruno',
            'last_name'     => 'Miranda Meira de Amorim',
            'email'         => 'bruno.miranda.meira.de.amorim@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Moledo',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions['PRE OFICIAL DO 2.ANO (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0354'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0355',
            'first_name'    => 'Ricardo',
            'last_name'     => 'Modesto Pires Rodrigues',
            'email'         => 'ricardo.modesto.pires.rodrigues@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Antime - Fafe',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'active',
            'department_id' => $dept->id,
            'position_id'   => $positions['CHEFE DE EQUIPA (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0355'] = $emp->id;
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['TET/BT-Redes     90h/119h/120h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Contagem de Energia BTE/MT 56h'], 'status' => 'completed']);

        $emp = Employee::create([
            'code'          => 'FUN0356',
            'first_name'    => 'Pedro',
            'last_name'     => 'Leonel Teixeira Silva',
            'email'         => 'pedro.leonel.teixeira.silva@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Antime - Fafe',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions['AJUDANTE DO 1.ANO (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0356'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0357',
            'first_name'    => 'Alberto',
            'last_name'     => 'Rodrigues Faria de Carvalho',
            'email'         => 'alberto.rodrigues.faria.de.carvalho@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Castelo do Neiva',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions['TÉCNICO ADMINISTRATIVO DE PRODUÇÃO GRAU II (CC)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0357'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0358',
            'first_name'    => 'Nuno',
            'last_name'     => 'Micael Gonçalves Costa',
            'email'         => 'nuno.micael.gon.alves.costa@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Travassós - Fafe',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'active',
            'department_id' => $dept->id,
            'position_id'   => $positions['OFICIAL ELECTRICISTA (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0358'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0359',
            'first_name'    => 'Ricardo',
            'last_name'     => 'Oliveira Moura',
            'email'         => 'ricardo.oliveira.moura@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Fafe',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions['PRE OFICIAL DO 2.ANO (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0359'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0360',
            'first_name'    => 'Fabien',
            'last_name'     => 'Fernandes',
            'email'         => 'fabien.fernandes@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Moledo',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions['AJUDANTE DO 1.ANO (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0360'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0361',
            'first_name'    => 'Justino',
            'last_name'     => 'Horácio Ferreira de Carvalho',
            'email'         => 'justino.hor.cio.ferreira.de.carvalho@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Nogueira',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions['OFICIAL ELECTRICISTA (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0361'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0362',
            'first_name'    => 'Fernanda',
            'last_name'     => 'Sofia de Freitas Coutinho',
            'email'         => 'fernanda.sofia.de.freitas.coutinho@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Viana do Castelo',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'active',
            'department_id' => $dept->id,
            'position_id'   => $positions['CHEFE DE DEPARTAMENTO'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0362'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0363',
            'first_name'    => 'José',
            'last_name'     => 'Manuel Pimenta Ribeiro',
            'email'         => 'jos.manuel.pimenta.ribeiro@hreminho.pt',
            'date_of_birth' => '1990-01-16',
            'nationality'   => null,
            'address'       => 'Celorico de Basto',
            'work_location' => 'Guimarães',
            'hire_date'     => '2023-01-02',
            'status'        => 'active',
            'department_id' => $dept->id,
            'position_id'   => $positions['ENCARREGADO (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0363'] = $emp->id;
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['TET/BT-Redes     90h/119h/120h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['INST CONT BTN_ Instalador de Contagem Baixa Tensão Normal (AQTSE)'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Contagem de Energia BTN 7h/14h/18h/21h/28h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Módulos Solares Fotovoltaicos _COD4588 50h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Noções de Higiene e Segurança no trabalho-Electricidade e electrónica_COD6040'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Segurança Eléctrica _COD6044'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Contagem BTN_COD8058'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Redes Inteligentes_COD8078'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Electricidade Geral _COD0932 50h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Equipamentos de movimentação de terras-Verificação e Ensaio_COD3927 25h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['COTS-Conduzir e Operar o Trator em Segurança 50h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Operador de Máquinas Agrícolas 1.200h'], 'status' => 'completed']);

        $emp = Employee::create([
            'code'          => 'FUN0364',
            'first_name'    => 'José',
            'last_name'     => 'Alberto Antunes Ferreira',
            'email'         => 'jos.alberto.antunes.ferreira@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Viana do Castelo',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'active',
            'department_id' => $dept->id,
            'position_id'   => $positions['CHEFE DE DEPARTAMENTO'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0364'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0365',
            'first_name'    => 'Nuno',
            'last_name'     => 'de Sousa Liquito',
            'email'         => 'nuno.de.sousa.liquito@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Vila de Punhe',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions['PRE OFICIAL DO 1.ANO (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0365'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0366',
            'first_name'    => 'Gilberto',
            'last_name'     => 'dos Santos Pereira',
            'email'         => 'gilberto.dos.santos.pereira@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Borba de Godim',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions['OFICIAL PRINCIPAL (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0366'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0367',
            'first_name'    => 'Fábio',
            'last_name'     => 'Vieira Gonçalves',
            'email'         => 'f.bio.vieira.gon.alves@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Viana do Castelo',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'active',
            'department_id' => $dept->id,
            'position_id'   => $positions['TIROCINANTE (TD)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0367'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0368',
            'first_name'    => 'Ricardo',
            'last_name'     => 'Manuel Moreira Alves',
            'email'         => 'ricardo.manuel.moreira.alves@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Fojo',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions['PRE OFICIAL DO 1.ANO (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0368'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0369',
            'first_name'    => 'Filipe',
            'last_name'     => 'Duarte Peixoto Lobo',
            'email'         => 'filipe.duarte.peixoto.lobo@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Aboim da Nobrega',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions['AJUDANTE DO 1.ANO (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0369'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0370',
            'first_name'    => 'José',
            'last_name'     => 'Manuel Oliveira Mendes',
            'email'         => 'jos.manuel.oliveira.mendes@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Refojos',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'active',
            'department_id' => $dept->id,
            'position_id'   => $positions['OFICIAL ELECTRICISTA (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0370'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0371',
            'first_name'    => 'Marco',
            'last_name'     => 'Rafael de Castro Branco',
            'email'         => 'marco.rafael.de.castro.branco@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Poço',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'active',
            'department_id' => $dept->id,
            'position_id'   => $positions['CHEFE DE EQUIPA (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0371'] = $emp->id;
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['TET/MT Método de Intervenção à Distância até 30KV-60h/360h/420'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Redes Aéreas         AT e MT - caracterização _COD8048'], 'status' => 'completed']);

        $emp = Employee::create([
            'code'          => 'FUN0372',
            'first_name'    => 'Rui',
            'last_name'     => 'António Mendes de Oliveira',
            'email'         => 'rui.ant.nio.mendes.de.oliveira@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Olela',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'active',
            'department_id' => $dept->id,
            'position_id'   => $positions['OFICIAL ELECTRICISTA (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0372'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0373',
            'first_name'    => 'Carlos',
            'last_name'     => 'Alberto da Silva Pereira',
            'email'         => 'carlos.alberto.da.silva.pereira@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Viana do Castelo',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions['OFICIAL ELECTRICISTA (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0373'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0374',
            'first_name'    => 'Edgar',
            'last_name'     => 'Eduardo Pereira Gonçalves',
            'email'         => 'edgar.eduardo.pereira.gon.alves@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Refojos',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions['ESTAGIÁRIO (TD)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0374'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0375',
            'first_name'    => 'Pedro',
            'last_name'     => 'Manuel Antunes da Costa',
            'email'         => 'pedro.manuel.antunes.da.costa@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Viana do Castelo',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions['AJUDANTE DO 1.ANO (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0375'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0376',
            'first_name'    => 'Ricardo',
            'last_name'     => 'Pais de Sousa Taxa Araujo',
            'email'         => 'ricardo.pais.de.sousa.taxa.araujo@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Vila Praia de Âncora',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions['ENGENHEIRO ELECTROTÉCNICO'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0376'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0377',
            'first_name'    => 'Nuno',
            'last_name'     => 'Miguel Sousa Teixeira',
            'email'         => 'nuno.miguel.sousa.teixeira@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Vila Nova de Gaia',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions['ASSISTENTE TECNICO GRAU II (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0377'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0378',
            'first_name'    => 'Paulo',
            'last_name'     => 'Nuno Sousa Faria Afonso',
            'email'         => 'paulo.nuno.sousa.faria.afonso@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Neiva',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions['ENGENHEIRO ELECTROTÉCNICO'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0378'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0379',
            'first_name'    => 'Paulo',
            'last_name'     => 'Jorge Costa Lima',
            'email'         => 'paulo.jorge.costa.lima@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Viana do Castelo',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions['ASSISTENTE TECNICO GRAU II (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0379'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0380',
            'first_name'    => 'Hugo',
            'last_name'     => 'Miguel Fernandes',
            'email'         => 'hugo.miguel.fernandes@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Cisão',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions['OFICIAL ELECTRICISTA (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0380'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0381',
            'first_name'    => 'Mario',
            'last_name'     => 'Jorge de Sousa Lopes',
            'email'         => 'mario.jorge.de.sousa.lopes@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Carrazeda de Ansiães',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions['OFICIAL ELECTRICISTA (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0381'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0382',
            'first_name'    => 'Jorge',
            'last_name'     => 'Floriano Magalhães Carvalho',
            'email'         => 'jorge.floriano.magalh.es.carvalho@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Carrazeda de Ansiães',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions['PRE OFICIAL DO 2.ANO (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0382'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0383',
            'first_name'    => 'Adriano',
            'last_name'     => 'Miguel Fevereiro',
            'email'         => 'adriano.miguel.fevereiro@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Felgar',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions['OFICIAL ELECTRICISTA (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0383'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0384',
            'first_name'    => 'António',
            'last_name'     => 'Adriano Fidalgo Andrade',
            'email'         => 'ant.nio.adriano.fidalgo.andrade@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Felgueiras',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions['OFICIAL ELECTRICISTA (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0384'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0385',
            'first_name'    => 'Armando',
            'last_name'     => 'António Fernandes Lourenço',
            'email'         => 'armando.ant.nio.fernandes.louren.o@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Mogadouro',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions['ENCARREGADO (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0385'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0386',
            'first_name'    => 'Carlos',
            'last_name'     => 'Alberto Nunes Maçorano',
            'email'         => 'carlos.alberto.nunes.ma.orano@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Quintas Quebradas',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions['OFICIAL ELECTRICISTA (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0386'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0387',
            'first_name'    => 'João',
            'last_name'     => 'Carlos Pires Gonçalves',
            'email'         => 'jo.o.carlos.pires.gon.alves@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Vale da Madre',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions['OFICIAL ELECTRICISTA (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0387'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0388',
            'first_name'    => 'João',
            'last_name'     => 'José Bento Soares',
            'email'         => 'jo.o.jos.bento.soares@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Zava - Mogadouro',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions['OFICIAL ELECTRICISTA (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0388'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0389',
            'first_name'    => 'José',
            'last_name'     => 'dos Santos Fevereiro',
            'email'         => 'jos.dos.santos.fevereiro@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Vila Nova de Foz Côa',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions['OFICIAL ELECTRICISTA (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0389'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0390',
            'first_name'    => 'Manuel',
            'last_name'     => 'António Fernandes',
            'email'         => 'manuel.ant.nio.fernandes@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Mogadouro',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions['OFICIAL ELECTRICISTA (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0390'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0391',
            'first_name'    => 'Nuno',
            'last_name'     => 'Luis Rentes Lagareiro',
            'email'         => 'nuno.luis.rentes.lagareiro@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Mogadouro',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions['OFICIAL ELECTRICISTA (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0391'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0392',
            'first_name'    => 'Abilio',
            'last_name'     => 'António Felgueiras Moreno',
            'email'         => 'abilio.ant.nio.felgueiras.moreno@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Mogadouro',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions['CONDUTOR MANOBRADOR DE EQUIPAMENTOS INDUSTRIAIS (NIVEL III)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0392'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0393',
            'first_name'    => 'Hugo',
            'last_name'     => 'Filipe Brito Rodrigues',
            'email'         => 'hugo.filipe.brito.rodrigues@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Portela de Vade',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'active',
            'department_id' => $dept->id,
            'position_id'   => $positions['OFICIAL ELECTRICISTA (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0393'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0394',
            'first_name'    => 'Sérgio',
            'last_name'     => 'Filipe da Costa Oliveira',
            'email'         => 's.rgio.filipe.da.costa.oliveira@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Poça da Carvalha',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions['AJUDANTE DO 1.ANO (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0394'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0395',
            'first_name'    => 'Bruno',
            'last_name'     => 'Miguel Cardoso Maia',
            'email'         => 'bruno.miguel.cardoso.maia@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Real',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions['AJUDANTE DO 1.ANO (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0395'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0396',
            'first_name'    => 'João',
            'last_name'     => 'Paulo Ribeiro Fernandes',
            'email'         => 'jo.o.paulo.ribeiro.fernandes@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'São Torcato',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions['AJUDANTE DO 2.ANO (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0396'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0397',
            'first_name'    => 'José',
            'last_name'     => 'Serafim Rego Canastra',
            'email'         => 'jos.serafim.rego.canastra@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Torre de Moncorvo',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions['OFICIAL ELECTRICISTA (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0397'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0398',
            'first_name'    => 'José',
            'last_name'     => 'Alberto dos Santos Pereira',
            'email'         => 'jos.alberto.dos.santos.pereira@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Zona Industrial',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions['OFICIAL ELECTRICISTA (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0398'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0399',
            'first_name'    => 'Jorge',
            'last_name'     => 'Miguel Sampaio Magalhães',
            'email'         => 'jorge.miguel.sampaio.magalh.es@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Tarrado',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions['CHEFE DE EQUIPA (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0399'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0400',
            'first_name'    => 'Pedro',
            'last_name'     => 'Manuel Sampaio Magalhães',
            'email'         => 'pedro.manuel.sampaio.magalh.es@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Cabeceiras de Basto',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions['OFICIAL ELECTRICISTA (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0400'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0401',
            'first_name'    => 'António',
            'last_name'     => 'Augusto da Silva Leite',
            'email'         => 'ant.nio.augusto.da.silva.leite@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Mota',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'active',
            'department_id' => $dept->id,
            'position_id'   => $positions['OFICIAL ELECTRICISTA (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0401'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0402',
            'first_name'    => 'Manuel',
            'last_name'     => 'Fernando Leite Moreira',
            'email'         => 'manuel.fernando.leite.moreira@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Funduães',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions['OFICIAL ELECTRICISTA (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0402'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0403',
            'first_name'    => 'António',
            'last_name'     => 'Amaro de Oliveira Pinto',
            'email'         => 'ant.nio.amaro.de.oliveira.pinto@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Cerca Nova',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions['OFICIAL ELECTRICISTA (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0403'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0404',
            'first_name'    => 'Fernando',
            'last_name'     => 'Manuel de Figueiredo Alves Gil',
            'email'         => 'fernando.manuel.de.figueiredo.alves.gil@hreminho.pt',
            'date_of_birth' => '1962-02-20',
            'nationality'   => null,
            'address'       => 'Carreira',
            'work_location' => null,
            'hire_date'     => '2016-10-03',
            'status'        => 'active',
            'department_id' => $dept->id,
            'position_id'   => $positions['ENCARREGADO (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0404'] = $emp->id;
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['TET/BT-Redes     90h/119h/120h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['TAR-Baixa Tensão 14h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Noções de Higiene e Segurança no trabalho-Electricidade e electrónica_COD6040'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Contagem BTN_COD8058'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Trabalhos em Tensão _COD8059'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Gruas 20h/8h'], 'status' => 'completed']);

        $emp = Employee::create([
            'code'          => 'FUN0405',
            'first_name'    => 'João',
            'last_name'     => 'Filipe Teixeira Rodrigues',
            'email'         => 'jo.o.filipe.teixeira.rodrigues@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Lemenhe',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions['OFICIAL ELECTRICISTA (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0405'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0406',
            'first_name'    => 'José',
            'last_name'     => 'Maria Pinto de Araujo',
            'email'         => 'jos.maria.pinto.de.araujo@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Jesufrei',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions['OFICIAL ELECTRICISTA (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0406'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0407',
            'first_name'    => 'Francisco',
            'last_name'     => 'Oliveira da Silva',
            'email'         => 'francisco.oliveira.da.silva@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Vila Nova de Famalicão',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions['CONDUTOR MANOBRADOR DE EQUIPAMENTOS INDUSTRIAIS (NIVEL III)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0407'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0408',
            'first_name'    => 'Pedro',
            'last_name'     => 'Miguel Gonçalves Neves',
            'email'         => 'pedro.miguel.gon.alves.neves@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Fafe',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions['PRE OFICIAL DO 2.ANO (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0408'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0409',
            'first_name'    => 'Fábio',
            'last_name'     => 'Dinis Rodrigues da Silva',
            'email'         => 'f.bio.dinis.rodrigues.da.silva@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Fafe',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'active',
            'department_id' => $dept->id,
            'position_id'   => $positions['OFICIAL ELECTRICISTA (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0409'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0410',
            'first_name'    => 'Telmo',
            'last_name'     => 'José Fernandes Castro',
            'email'         => 'telmo.jos.fernandes.castro@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Fafe',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'active',
            'department_id' => $dept->id,
            'position_id'   => $positions['OFICIAL ELECTRICISTA (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0410'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0411',
            'first_name'    => 'Manuel',
            'last_name'     => 'Tomé Lopes de Freitas',
            'email'         => 'manuel.tom.lopes.de.freitas@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Travassós',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'active',
            'department_id' => $dept->id,
            'position_id'   => $positions['CONDUTOR MANOBRADOR DE EQUIPAMENTOS INDUSTRIAIS (NIVEL III)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0411'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0412',
            'first_name'    => 'Bruno',
            'last_name'     => 'Miguel Freitas Castro',
            'email'         => 'bruno.miguel.freitas.castro@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Fafe',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'active',
            'department_id' => $dept->id,
            'position_id'   => $positions['CHEFE DE EQUIPA (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0412'] = $emp->id;
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['TET/BT-Redes     90h/119h/120h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['TAR-Baixa Tensão 14h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Módulos Solares Fotovoltaicos _COD4588 50h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Noções de Higiene e Segurança no trabalho-Electricidade e electrónica_COD6040'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Segurança Eléctrica _COD6044'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Manutenção e Reparaç.de avarias em redes BT e IP_COD8057'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Contagem BTN_COD8058'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Trabalhos em Tensão _COD8059'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Redes Inteligentes_COD8078'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Electricidade Geral _COD0932 50h'], 'status' => 'completed']);

        $emp = Employee::create([
            'code'          => 'FUN0413',
            'first_name'    => 'Pedro',
            'last_name'     => 'Manuel Freitas Castro',
            'email'         => 'pedro.manuel.freitas.castro@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Travassós',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions['OFICIAL ELECTRICISTA (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0413'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0414',
            'first_name'    => 'António',
            'last_name'     => 'Teixeira Pacheco',
            'email'         => 'ant.nio.teixeira.pacheco@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Olela - Cabeceira de Basto',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'active',
            'department_id' => $dept->id,
            'position_id'   => $positions['OFICIAL ELECTRICISTA (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0414'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0415',
            'first_name'    => 'Hermenegildo',
            'last_name'     => 'Daniel Oliveira e Silva',
            'email'         => 'hermenegildo.daniel.oliveira.e.silva@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Landim',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions['OFICIAL ELECTRICISTA (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0415'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0416',
            'first_name'    => 'Joaquim',
            'last_name'     => 'de Pinho Teixeira',
            'email'         => 'joaquim.de.pinho.teixeira@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Castelo de Paiva',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions['AJUDANTE DO 1.ANO (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0416'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0417',
            'first_name'    => 'Américo',
            'last_name'     => 'Filpe Moreira Silva',
            'email'         => 'am.rico.filpe.moreira.silva@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Paredes',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions['AJUDANTE DO 1.ANO (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0417'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0418',
            'first_name'    => 'Pedro',
            'last_name'     => 'Miguel Moreira de Jesus',
            'email'         => 'pedro.miguel.moreira.de.jesus@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Paredes',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions['CHEFE DE EQUIPA (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0418'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0419',
            'first_name'    => 'Flávio',
            'last_name'     => 'Joaquim Sousa Meireles',
            'email'         => 'fl.vio.joaquim.sousa.meireles@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Paredes',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions['PRE OFICIAL DO 1.ANO (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0419'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0420',
            'first_name'    => 'Domingos',
            'last_name'     => 'Lopes de Oliveira',
            'email'         => 'domingos.lopes.de.oliveira@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Fonte Coberta',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions['CHEFE DE EQUIPA (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0420'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0421',
            'first_name'    => 'Pedro',
            'last_name'     => 'Miguel Barroso da Silva',
            'email'         => 'pedro.miguel.barroso.da.silva@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Macieira de Rates',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions['AJUDANTE DO 1.ANO (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0421'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0422',
            'first_name'    => 'José',
            'last_name'     => 'André Oliveira Fernandes',
            'email'         => 'jos.andr.oliveira.fernandes@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Guimarães',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions['AJUDANTE DO 2.ANO (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0422'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0423',
            'first_name'    => 'Carlos',
            'last_name'     => 'Manuel Ribeiro Pacheco',
            'email'         => 'carlos.manuel.ribeiro.pacheco@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Gondalães',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions['CHEFE DE EQUIPA (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0423'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0424',
            'first_name'    => 'Hugo',
            'last_name'     => 'Rafael Ferreira Ribeiro',
            'email'         => 'hugo.rafael.ferreira.ribeiro@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Guilhufe',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions['CHEFE DE EQUIPA (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0424'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0425',
            'first_name'    => 'José',
            'last_name'     => 'Dionísio Pinto da Rocha',
            'email'         => 'jos.dion.sio.pinto.da.rocha@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Capela',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions['CHEFE DE EQUIPA (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0425'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0426',
            'first_name'    => 'Pedro',
            'last_name'     => 'Emanuel Ferreira Ribeiro',
            'email'         => 'pedro.emanuel.ferreira.ribeiro@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Guilhufe',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions['CHEFE DE EQUIPA (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0426'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0427',
            'first_name'    => 'Roberto',
            'last_name'     => 'Carlos de Sousa Pacheco',
            'email'         => 'roberto.carlos.de.sousa.pacheco@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Bitarães',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions['CHEFE DE EQUIPA (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0427'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0428',
            'first_name'    => 'Tiago',
            'last_name'     => 'José Matos Moreira',
            'email'         => 'tiago.jos.matos.moreira@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Duas Igrejas',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions['CHEFE DE EQUIPA (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0428'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0429',
            'first_name'    => 'Roberto',
            'last_name'     => 'Filipe Ribeiro Silva',
            'email'         => 'roberto.filipe.ribeiro.silva@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Areal',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'active',
            'department_id' => $dept->id,
            'position_id'   => $positions['OFICIAL ELECTRICISTA (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0429'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0430',
            'first_name'    => 'Luís',
            'last_name'     => 'Carlos Costa Gonçalves',
            'email'         => 'lu.s.carlos.costa.gon.alves@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Ribeiros',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'active',
            'department_id' => $dept->id,
            'position_id'   => $positions['OFICIAL ELECTRICISTA (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0430'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0431',
            'first_name'    => 'Rui',
            'last_name'     => 'Miguel Mendes Peixoto',
            'email'         => 'rui.miguel.mendes.peixoto@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Travassós',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions['PRE OFICIAL DO 1.ANO (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0431'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0432',
            'first_name'    => 'Flávio',
            'last_name'     => 'Manuel Pereira Teixeira',
            'email'         => 'fl.vio.manuel.pereira.teixeira@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Bemposta',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'active',
            'department_id' => $dept->id,
            'position_id'   => $positions['OFICIAL ELECTRICISTA (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0432'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0433',
            'first_name'    => 'José',
            'last_name'     => 'Rafael Carvalho Lopes',
            'email'         => 'jos.rafael.carvalho.lopes@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Guimarães',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions['PRE OFICIAL DO 2.ANO (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0433'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0434',
            'first_name'    => 'Eusébio',
            'last_name'     => 'Guimarães Pereira Fernandes',
            'email'         => 'eus.bio.guimar.es.pereira.fernandes@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Fafe',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions['AJUDANTE DO 2.ANO (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0434'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0435',
            'first_name'    => 'Vitor',
            'last_name'     => 'Miguel Ramos Corucho',
            'email'         => 'vitor.miguel.ramos.corucho@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Viana do Castelo',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions['PRE OFICIAL DO 1.ANO (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0435'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0436',
            'first_name'    => 'Jorge',
            'last_name'     => 'Miguel Cruz Guerreiro',
            'email'         => 'jorge.miguel.cruz.guerreiro@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Viana do Castelo',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions['CHEFE DE EQUIPA (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0436'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0437',
            'first_name'    => 'Pedro',
            'last_name'     => 'Miguel Martins Barroso',
            'email'         => 'pedro.miguel.martins.barroso@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Vieira do Minho',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'active',
            'department_id' => $dept->id,
            'position_id'   => $positions['OFICIAL ELECTRICISTA (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0437'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0438',
            'first_name'    => 'Vítor',
            'last_name'     => 'Manuel de Barros Silva',
            'email'         => 'v.tor.manuel.de.barros.silva@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Real Cima',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'active',
            'department_id' => $dept->id,
            'position_id'   => $positions['OFICIAL ELECTRICISTA (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0438'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0439',
            'first_name'    => 'Vera',
            'last_name'     => 'Lúcia Rodrigues de Oliveira',
            'email'         => 'vera.l.cia.rodrigues.de.oliveira@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Deão',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'active',
            'department_id' => $dept->id,
            'position_id'   => $positions['CHEFE DE DEPARTAMENTO'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0439'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0440',
            'first_name'    => 'Ana',
            'last_name'     => 'Cláudia Lopes da Silva',
            'email'         => 'ana.cl.udia.lopes.da.silva@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Ponte',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'active',
            'department_id' => $dept->id,
            'position_id'   => $positions['ESCRITURARIO DE 2. (ESC)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0440'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0441',
            'first_name'    => 'Ricardo',
            'last_name'     => 'João Ribeiro Soares',
            'email'         => 'ricardo.jo.o.ribeiro.soares@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Fafe',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions['PRE OFICIAL DO 2.ANO (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0441'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0442',
            'first_name'    => 'Ricardo',
            'last_name'     => 'Manuel Silva Lopes',
            'email'         => 'ricardo.manuel.silva.lopes@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Fafe',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions['PRE OFICIAL DO 2.ANO (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0442'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0443',
            'first_name'    => 'Carlos',
            'last_name'     => 'André Oliveira Teixeira',
            'email'         => 'carlos.andr.oliveira.teixeira@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Refojos',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions['PRE OFICIAL DO 2.ANO (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0443'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0444',
            'first_name'    => 'José',
            'last_name'     => 'Manuel Puga Rodrigues',
            'email'         => 'jos.manuel.puga.rodrigues@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Arribão',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions['CHEFE DE EQUIPA (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0444'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0445',
            'first_name'    => 'Filipe',
            'last_name'     => 'Domingos Magalhães Ferraz',
            'email'         => 'filipe.domingos.magalh.es.ferraz@hreminho.pt',
            'date_of_birth' => '1996-05-01',
            'nationality'   => null,
            'address'       => 'Cabeceiras de Basto',
            'work_location' => 'Guimarães',
            'hire_date'     => '2010-01-01',
            'status'        => 'active',
            'department_id' => $dept->id,
            'position_id'   => $positions['CHEFE DE EQUIPA (EL)'],
            'sector_id'     => $sectors['Renováveis'],
        ]);
        $empMap['FUN0445'] = $emp->id;
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['TAR 21h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['STAC- Segurança em Trabalhos e Altura-Coberturas, Telhados e Fachadas 8h/16h/14h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Projeto de Sistema Solar Fotovoltaico-CONSTRUÇÃO_          COD4590 50h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Projeto de Sistema Solar Fotovoltaico-INSTALAÇÃO _COD4591'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Electricidade Geral _COD0932 50h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Eficiência Energética e Energia Renováveis_COD9282'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Empilhadores 20h/8h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Plataformas Elevatórias    8h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Sensibilização 1ºos Socorros 7h/8h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Suporte Básico de Vida 4h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Instalações, Quadros e Redes Eléctricas e Fotovoltaicas Residenciais e Fabris de Peq. Ou Média Dimensão 16h'], 'status' => 'completed']);

        $emp = Employee::create([
            'code'          => 'FUN0446',
            'first_name'    => 'Edgar',
            'last_name'     => 'Abilio Fernandes Carvalho',
            'email'         => 'edgar.abilio.fernandes.carvalho@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Freitas',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'active',
            'department_id' => $dept->id,
            'position_id'   => $positions['OFICIAL ELECTRICISTA (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0446'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0447',
            'first_name'    => 'Helena',
            'last_name'     => 'Patrícia de Sousa Marques da Silva',
            'email'         => 'helena.patr.cia.de.sousa.marques.da.silva@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Ponte',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions['OFICIAL ELECTRICISTA (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0447'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0448',
            'first_name'    => 'Luis',
            'last_name'     => 'Miguel Costa Lopes',
            'email'         => 'luis.miguel.costa.lopes@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Fafe',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'active',
            'department_id' => $dept->id,
            'position_id'   => $positions['OFICIAL ELECTRICISTA (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0448'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0449',
            'first_name'    => 'Rafael',
            'last_name'     => 'José Antunes Machado',
            'email'         => 'rafael.jos.antunes.machado@hreminho.pt',
            'date_of_birth' => '2000-10-07',
            'nationality'   => null,
            'address'       => 'Viana do Castelo',
            'work_location' => 'Viana',
            'hire_date'     => '2010-01-01',
            'status'        => 'active',
            'department_id' => $dept->id,
            'position_id'   => $positions['OFICIAL ELECTRICISTA (EL)'],
            'sector_id'     => $sectors['Renováveis'],
        ]);
        $empMap['FUN0449'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0450',
            'first_name'    => 'Sérgio',
            'last_name'     => 'André Costa Leite',
            'email'         => 's.rgio.andr.costa.leite@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Golães - Fafe',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'active',
            'department_id' => $dept->id,
            'position_id'   => $positions['CHEFE DE EQUIPA (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0450'] = $emp->id;
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['LIT (VÁLIDO)'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['TET/BT-Redes     90h/119h/120h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Execução de Redes Subterrâneas de MT-Ligações 14h/35'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Noções de Higiene e Segurança no trabalho-Electricidade e electrónica_COD6040'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Manutenção e Reparaç.de avarias em redes BT e IP_COD8057'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Contagem BTN_COD8058'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Trabalhos em Tensão _COD8059'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Plataformas Elevatórias    8h'], 'status' => 'completed']);

        $emp = Employee::create([
            'code'          => 'FUN0451',
            'first_name'    => 'José',
            'last_name'     => 'Joaquim Ferraz Gonçalves Santos',
            'email'         => 'jos.joaquim.ferraz.gon.alves.santos@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Cabeceiras de Basto',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'active',
            'department_id' => $dept->id,
            'position_id'   => $positions['OFICIAL ELECTRICISTA (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0451'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0452',
            'first_name'    => 'José',
            'last_name'     => 'Manuel Teixeira da Silva Marinho',
            'email'         => 'jos.manuel.teixeira.da.silva.marinho@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Santa Eulália',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions['PRE OFICIAL DO 2.ANO (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0452'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0453',
            'first_name'    => 'Ricardo',
            'last_name'     => 'José Machado Barros da Silva',
            'email'         => 'ricardo.jos.machado.barros.da.silva@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Viana do Castelo',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions['AJUDANTE DO 1.ANO (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0453'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0454',
            'first_name'    => 'João',
            'last_name'     => 'de Jesus Fernandes',
            'email'         => 'jo.o.de.jesus.fernandes@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Estorãos',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'active',
            'department_id' => $dept->id,
            'position_id'   => $positions['OFICIAL ELECTRICISTA (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0454'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0455',
            'first_name'    => 'Fábio',
            'last_name'     => 'Alexandre Ferreira Gonçalves',
            'email'         => 'f.bio.alexandre.ferreira.gon.alves@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Fafe',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'active',
            'department_id' => $dept->id,
            'position_id'   => $positions['CHEFE DE EQUIPA (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0455'] = $emp->id;
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['LIT (VÁLIDO)'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['TET/BT-Redes     90h/119h/120h'], 'status' => 'completed']);

        $emp = Employee::create([
            'code'          => 'FUN0456',
            'first_name'    => 'Ricardo',
            'last_name'     => 'Lima Sales Gomes',
            'email'         => 'ricardo.lima.sales.gomes@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Alvarães',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions['AJUDANTE DO 1.ANO (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0456'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0457',
            'first_name'    => 'João',
            'last_name'     => 'Pedro Leitão Pereira',
            'email'         => 'jo.o.pedro.leit.o.pereira@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Vilela',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions['PRE OFICIAL DO 1.ANO (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0457'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0458',
            'first_name'    => 'Bruno',
            'last_name'     => 'Ramos Gavina',
            'email'         => 'bruno.ramos.gavina@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Mindelo',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions['ASSISTENTE TÉCNICO GRAU I (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0458'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0459',
            'first_name'    => 'Fernando',
            'last_name'     => 'Vitor Lopes Pereira',
            'email'         => 'fernando.vitor.lopes.pereira@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Estorãos',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions['OFICIAL ELECTRICISTA (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0459'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0460',
            'first_name'    => 'Miguel',
            'last_name'     => 'Ângelo de Almeida Lopes',
            'email'         => 'miguel.ngelo.de.almeida.lopes@hreminho.pt',
            'date_of_birth' => '1984-10-13',
            'nationality'   => null,
            'address'       => 'Guimarães',
            'work_location' => 'Guimarães',
            'hire_date'     => '2010-01-01',
            'status'        => 'active',
            'department_id' => $dept->id,
            'position_id'   => $positions['CHEFE DE EQUIPA (EL)'],
            'sector_id'     => $sectors['Renováveis'],
        ]);
        $empMap['FUN0460'] = $emp->id;
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Mobilidade Eléctrica EDP'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['STAC- Segurança em Trabalhos e Altura-Coberturas, Telhados e Fachadas 8h/16h/14h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Projeto de Sistema Solar Fotovoltaico-CONSTRUÇÃO_          COD4590 50h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Projeto de Sistema Solar Fotovoltaico-INSTALAÇÃO _COD4591'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Electricidade Geral _COD0932 50h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Eficiência Energética e Energia Renováveis_COD9282'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Retroescav /Escavadora 8h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Empilhadores 20h/8h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Plataformas Elevatórias    8h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Sensibilização 1ºos Socorros 7h/8h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Suporte Básico de Vida 4h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Instalações, Quadros e Redes Eléctricas e Fotovoltaicas Residenciais e Fabris de Peq. Ou Média Dimensão 16h'], 'status' => 'completed']);

        $emp = Employee::create([
            'code'          => 'FUN0461',
            'first_name'    => 'Luis',
            'last_name'     => 'Henrique da Costa Magalhães',
            'email'         => 'luis.henrique.da.costa.magalh.es@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Ponte',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions['AJUDANTE DO 1.ANO (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0461'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0462',
            'first_name'    => 'José',
            'last_name'     => 'Manuel Carvalho da Costa',
            'email'         => 'jos.manuel.carvalho.da.costa@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Barcelos',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'active',
            'department_id' => $dept->id,
            'position_id'   => $positions['CHEFE DE DEPARTAMENTO'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0462'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0463',
            'first_name'    => 'Luís',
            'last_name'     => 'Emanuel Alves Magalhães',
            'email'         => 'lu.s.emanuel.alves.magalh.es@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Fafe',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions['CONDUTOR MANOBRADOR DE EQUIPAMENTOS INDUSTRIAIS (NIVEL III)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0463'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0464',
            'first_name'    => 'Luís',
            'last_name'     => 'Filipe Santos Salgado',
            'email'         => 'lu.s.filipe.santos.salgado@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Golães',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions['PRE OFICIAL DO 2.ANO (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0464'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0465',
            'first_name'    => 'Lino',
            'last_name'     => 'Manuel Meireles Parente',
            'email'         => 'lino.manuel.meireles.parente@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Viana do Castelo',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions['AJUDANTE DO 2.ANO (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0465'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0466',
            'first_name'    => 'Nuno',
            'last_name'     => 'Gomes Correia',
            'email'         => 'nuno.gomes.correia@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Viana do Castelo',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions['AJUDANTE DO 1.ANO (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0466'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0467',
            'first_name'    => 'José',
            'last_name'     => 'Pedro da Silva Moreira',
            'email'         => 'jos.pedro.da.silva.moreira@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Viana do Castelo',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions['APRENDIZ DO 1.ANO (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0467'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0468',
            'first_name'    => 'Bruno',
            'last_name'     => 'Miguel de Oliveira de Freitas',
            'email'         => 'bruno.miguel.de.oliveira.de.freitas@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Guimarães',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions['CONDUTOR MANOBRADOR DE EQUIPAMENTOS INDUSTRIAIS (NIVEL III)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0468'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0469',
            'first_name'    => 'Tiago',
            'last_name'     => 'Filipe Novais de Araujo',
            'email'         => 'tiago.filipe.novais.de.araujo@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Guimarães',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions['FIEL DE ARMAZEM (COM)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0469'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0470',
            'first_name'    => 'João',
            'last_name'     => 'Manuel Passos Pires',
            'email'         => 'jo.o.manuel.passos.pires@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Viana do Castelo',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions['AJUDANTE DO 1.ANO (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0470'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0471',
            'first_name'    => 'Tiago',
            'last_name'     => 'André Magalhães de Sousa',
            'email'         => 'tiago.andr.magalh.es.de.sousa@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Borba da Montanha',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions['AJUDANTE DO 1.ANO (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0471'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0472',
            'first_name'    => 'Rafael',
            'last_name'     => 'Henrique Ales Costa',
            'email'         => 'rafael.henrique.ales.costa@hreminho.pt',
            'date_of_birth' => '1988-10-08',
            'nationality'   => null,
            'address'       => 'Viana do Castelo',
            'work_location' => 'Viana',
            'hire_date'     => '2010-01-01',
            'status'        => 'active',
            'department_id' => $dept->id,
            'position_id'   => $positions['CHEFE DE EQUIPA (EL)'],
            'sector_id'     => $sectors['AVAC'],
        ]);
        $empMap['FUN0472'] = $emp->id;
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Noções de Higiene e Segurança no trabalho-Electricidade e electrónica_COD6040'], 'status' => 'completed']);

        $emp = Employee::create([
            'code'          => 'FUN0473',
            'first_name'    => 'Márcio',
            'last_name'     => 'André da Rocha Ribeiro',
            'email'         => 'm.rcio.andr.da.rocha.ribeiro@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Deão',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions['PRE OFICIAL DO 2.ANO (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0473'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0474',
            'first_name'    => 'Daniel',
            'last_name'     => 'Portela de Carvalho',
            'email'         => 'daniel.portela.de.carvalho@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Barcelos',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'active',
            'department_id' => $dept->id,
            'position_id'   => $positions['CHEFE DE DEPARTAMENTO'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0474'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0475',
            'first_name'    => 'Luís',
            'last_name'     => 'Miguel Rodrigues da Silva',
            'email'         => 'lu.s.miguel.rodrigues.da.silva@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Guimarães',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions['AJUDANTE DO 2.ANO (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0475'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0476',
            'first_name'    => 'João',
            'last_name'     => 'Alexandre Pereira Vaz',
            'email'         => 'jo.o.alexandre.pereira.vaz@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Vinhós',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'active',
            'department_id' => $dept->id,
            'position_id'   => $positions['PRE OFICIAL DO 1.ANO (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0476'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0477',
            'first_name'    => 'Fernando',
            'last_name'     => 'da Costa Martins',
            'email'         => 'fernando.da.costa.martins@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Gonça',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'active',
            'department_id' => $dept->id,
            'position_id'   => $positions['CHEFE DE EQUIPA (CC)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0477'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0478',
            'first_name'    => 'Flávio',
            'last_name'     => 'Pires Silva',
            'email'         => 'fl.vio.pires.silva@hreminho.pt',
            'date_of_birth' => '1995-03-11',
            'nationality'   => null,
            'address'       => 'São Clemente de Silvares',
            'work_location' => 'Guimarães',
            'hire_date'     => '2010-01-01',
            'status'        => 'active',
            'department_id' => $dept->id,
            'position_id'   => $positions['CHEFE DE EQUIPA (EL)'],
            'sector_id'     => $sectors['Renováveis'],
        ]);
        $empMap['FUN0478'] = $emp->id;
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Mobilidade Eléctrica EDP'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['STAC- Segurança em Trabalhos e Altura-Coberturas, Telhados e Fachadas 8h/16h/14h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Plataformas Elevatórias    8h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Sensibilização 1ºos Socorros 7h/8h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Suporte Básico de Vida 4h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Instalações, Quadros e Redes Eléctricas e Fotovoltaicas Residenciais e Fabris de Peq. Ou Média Dimensão 16h'], 'status' => 'completed']);

        $emp = Employee::create([
            'code'          => 'FUN0479',
            'first_name'    => 'Gonçalo',
            'last_name'     => 'Rodrigues Barreto',
            'email'         => 'gon.alo.rodrigues.barreto@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Vila Nova de Anha',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions['TÉCNICO OPERACIONAL GRAU I (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0479'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0480',
            'first_name'    => 'Eduardo',
            'last_name'     => 'Beltrame',
            'email'         => 'eduardo.beltrame@hreminho.pt',
            'date_of_birth' => '1991-08-21',
            'nationality'   => 'BR',
            'address'       => 'S. Faustino, Guimarães',
            'work_location' => 'Guimarães',
            'hire_date'     => '2021-03-01',
            'status'        => 'active',
            'department_id' => $dept->id,
            'position_id'   => $positions['ENCARREGADO (EL)'],
            'sector_id'     => $sectors['Renováveis'],
        ]);
        $empMap['FUN0480'] = $emp->id;
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['STAC- Segurança em Trabalhos e Altura-Coberturas, Telhados e Fachadas 8h/16h/14h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Retroescav /Escavadora 8h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Plataformas Elevatórias    8h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Sensibilização 1ºos Socorros 7h/8h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Suporte Básico de Vida 4h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Instalações, Quadros e Redes Eléctricas e Fotovoltaicas Residenciais e Fabris de Peq. Ou Média Dimensão 16h'], 'status' => 'completed']);

        $emp = Employee::create([
            'code'          => 'FUN0481',
            'first_name'    => 'Lucas',
            'last_name'     => 'Baptista Silva Ribeiro',
            'email'         => 'lucas.baptista.silva.ribeiro@hreminho.pt',
            'date_of_birth' => '2000-06-25',
            'nationality'   => null,
            'address'       => 'Soutelo',
            'work_location' => 'Guimarães',
            'hire_date'     => '2010-01-01',
            'status'        => 'active',
            'department_id' => $dept->id,
            'position_id'   => $positions['CHEFE DE EQUIPA (EL)'],
            'sector_id'     => $sectors['Renováveis'],
        ]);
        $empMap['FUN0481'] = $emp->id;
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['STAC- Segurança em Trabalhos e Altura-Coberturas, Telhados e Fachadas 8h/16h/14h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Plataformas Elevatórias    8h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Sensibilização 1ºos Socorros 7h/8h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Instalações, Quadros e Redes Eléctricas e Fotovoltaicas Residenciais e Fabris de Peq. Ou Média Dimensão 16h'], 'status' => 'completed']);

        $emp = Employee::create([
            'code'          => 'FUN0482',
            'first_name'    => 'João',
            'last_name'     => 'Miguel de Araújo',
            'email'         => 'jo.o.miguel.de.ara.jo@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Viana do Castelo',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions['PRE OFICIAL DO 1.ANO (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0482'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0483',
            'first_name'    => 'João',
            'last_name'     => 'Paulino de Lima Júnior',
            'email'         => 'jo.o.paulino.de.lima.j.nior@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Viana do Castelo',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions['AJUDANTE DO 2.ANO (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0483'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0484',
            'first_name'    => 'Carlos',
            'last_name'     => 'Manuel Cunha dos Santos',
            'email'         => 'carlos.manuel.cunha.dos.santos@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Fafe',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'active',
            'department_id' => $dept->id,
            'position_id'   => $positions['OFICIAL ELECTRICISTA (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0484'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0485',
            'first_name'    => 'Tiago',
            'last_name'     => 'José Castro Marques',
            'email'         => 'tiago.jos.castro.marques@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Fafe',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions['PRE OFICIAL DO 2.ANO (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0485'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0486',
            'first_name'    => 'André',
            'last_name'     => 'Fernandes Cunha',
            'email'         => 'andr.fernandes.cunha@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Fafe',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions['PRE OFICIAL DO 2.ANO (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0486'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0487',
            'first_name'    => 'Wagner',
            'last_name'     => 'Henrique de Azevedo',
            'email'         => 'wagner.henrique.de.azevedo@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Trofa',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions['OFICIAL ELECTRICISTA (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0487'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0488',
            'first_name'    => 'Adalberto',
            'last_name'     => 'Freitas Teixeira',
            'email'         => 'adalberto.freitas.teixeira@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Fafe',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'active',
            'department_id' => $dept->id,
            'position_id'   => $positions['SERRALHEIRO CIVIL DE 1. (MET)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0488'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0489',
            'first_name'    => 'Glenn',
            'last_name'     => 'Luciano Ferreira Palacios',
            'email'         => 'glenn.luciano.ferreira.palacios@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Viana do Castelo',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions['AJUDANTE DO 2.ANO (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0489'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0490',
            'first_name'    => 'Paulo',
            'last_name'     => 'Fernando Pereira de Brito',
            'email'         => 'paulo.fernando.pereira.de.brito@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Creixomil-Guimarães',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions['PRE OFICIAL DO 1.ANO (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0490'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0491',
            'first_name'    => 'Pedro',
            'last_name'     => 'Manuel Moreira Fernandes',
            'email'         => 'pedro.manuel.moreira.fernandes@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Fafe',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'active',
            'department_id' => $dept->id,
            'position_id'   => $positions['OFICIAL ELECTRICISTA (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0491'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0492',
            'first_name'    => 'Rui',
            'last_name'     => 'Vítor da Costa e Sousa',
            'email'         => 'rui.v.tor.da.costa.e.sousa@hreminho.pt',
            'date_of_birth' => '1981-07-30',
            'nationality'   => null,
            'address'       => 'Guimarães',
            'work_location' => 'Guimarães',
            'hire_date'     => '2010-01-01',
            'status'        => 'active',
            'department_id' => $dept->id,
            'position_id'   => $positions['CHEFE DE EQUIPA (EL)'],
            'sector_id'     => $sectors['Renováveis'],
        ]);
        $empMap['FUN0492'] = $emp->id;
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['TAR-Baixa Tensão 14h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Projeto de Sistema Solar Fotovoltaico-INSTALAÇÃO _COD4591'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Electricidade Geral _COD0932 50h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Eficiência Energética e Energia Renováveis_COD9282'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Plataformas Elevatórias    8h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Sensibilização 1ºos Socorros 7h/8h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Suporte Básico de Vida 4h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Instalações, Quadros e Redes Eléctricas e Fotovoltaicas Residenciais e Fabris de Peq. Ou Média Dimensão 16h'], 'status' => 'completed']);

        $emp = Employee::create([
            'code'          => 'FUN0493',
            'first_name'    => 'Carlos',
            'last_name'     => 'Manuel da Rocha Oliveira',
            'email'         => 'carlos.manuel.da.rocha.oliveira@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Penafiel',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions['PEDREIRO DE 1. (CC)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0493'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0494',
            'first_name'    => 'André',
            'last_name'     => 'Daniel Lourenço Cunha',
            'email'         => 'andr.daniel.louren.o.cunha@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Penafiel',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions['ENCARREGADO (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0494'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0495',
            'first_name'    => 'Isac',
            'last_name'     => 'David Moreira Vieira',
            'email'         => 'isac.david.moreira.vieira@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Penafiel',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions['CONDUTOR MANOBRADOR DE EQUUIPAMENTOS INDUSTRIAIS (NIVEL II)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0495'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0496',
            'first_name'    => 'Rafael',
            'last_name'     => 'João Castro Mesquita',
            'email'         => 'rafael.jo.o.castro.mesquita@hreminho.pt',
            'date_of_birth' => '2000-10-02',
            'nationality'   => null,
            'address'       => 'Cabeceiras de Basto',
            'work_location' => 'Guimarães',
            'hire_date'     => '2010-01-01',
            'status'        => 'active',
            'department_id' => $dept->id,
            'position_id'   => $positions['CHEFE DE EQUIPA (EL)'],
            'sector_id'     => $sectors['Renováveis'],
        ]);
        $empMap['FUN0496'] = $emp->id;
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Plataformas Elevatórias    8h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['(Carteira de Aptidão) Condução Veículos Agrícolas 25h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['(Carteira de Aptidão) Verificação Operação e Circulação c/Equipamentos de Elevação 25h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['(Carteira de Aptidão) Equipamentos de Movimentação de Terras Verificação 25h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Sensibilização 1ºos Socorros 7h/8h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Suporte Básico de Vida 4h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Instalações, Quadros e Redes Eléctricas e Fotovoltaicas Residenciais e Fabris de Peq. Ou Média Dimensão 16h'], 'status' => 'completed']);

        $emp = Employee::create([
            'code'          => 'FUN0497',
            'first_name'    => 'Italo',
            'last_name'     => 'Rochstroch Thomaz Silveira',
            'email'         => 'italo.rochstroch.thomaz.silveira@hreminho.pt',
            'date_of_birth' => '1996-09-27',
            'nationality'   => 'BR',
            'address'       => 'Braga',
            'work_location' => 'Guimarães',
            'hire_date'     => '2010-01-01',
            'status'        => 'active',
            'department_id' => $dept->id,
            'position_id'   => $positions['CHEFE DE EQUIPA (EL)'],
            'sector_id'     => $sectors['Renováveis/AVAC'],
        ]);
        $empMap['FUN0497'] = $emp->id;
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['TAR-Baixa Tensão 14h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Plataformas Elevatórias    8h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Sensibilização 1ºos Socorros 7h/8h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Suporte Básico de Vida 4h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Instalações, Quadros e Redes Eléctricas e Fotovoltaicas Residenciais e Fabris de Peq. Ou Média Dimensão 16h'], 'status' => 'completed']);

        $emp = Employee::create([
            'code'          => 'FUN0498',
            'first_name'    => 'Isabel',
            'last_name'     => 'da Conceição Oliveira de Sá Carvalheira',
            'email'         => 'isabel.da.concei.o.oliveira.de.s.carvalheira@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Póvoa de Varzim',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions['CHEFE DE DEPARTAMENTO'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0498'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0499',
            'first_name'    => 'José',
            'last_name'     => 'António Moreira Leitão',
            'email'         => 'jos.ant.nio.moreira.leit.o@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Penafiel',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions['CONDUTOR MANOBRADOR DE EQUUIPAMENTOS INDUSTRIAIS (NIVEL II)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0499'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0500',
            'first_name'    => 'Joel',
            'last_name'     => 'Rodrigues Esteves',
            'email'         => 'joel.rodrigues.esteves@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Póvoa do Vazim',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions['CHEFE DE DEPARTAMENTO'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0500'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0501',
            'first_name'    => 'David',
            'last_name'     => 'Ricardo Fernandes',
            'email'         => 'david.ricardo.fernandes@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Viana do Castelo',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'active',
            'department_id' => $dept->id,
            'position_id'   => $positions['TÉCNICO GRAU II'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0501'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0502',
            'first_name'    => 'Mário',
            'last_name'     => 'Jorge Costa Lopes',
            'email'         => 'm.rio.jorge.costa.lopes@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Fafe',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions['AJUDANTE DO 1.ANO (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0502'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0503',
            'first_name'    => 'Manuel',
            'last_name'     => 'Costa Campos',
            'email'         => 'manuel.costa.campos@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Vila Nova de Famalicão',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'active',
            'department_id' => $dept->id,
            'position_id'   => $positions['PRE OFICIAL DO 1.ANO (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0503'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0504',
            'first_name'    => 'Óscar',
            'last_name'     => 'José da Silva Carneiro',
            'email'         => 'scar.jos.da.silva.carneiro@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Vila Nova de Famalicão',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'active',
            'department_id' => $dept->id,
            'position_id'   => $positions['OFICIAL ELECTRICISTA (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0504'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0505',
            'first_name'    => 'Paulo',
            'last_name'     => 'Sérgio Costa Nogueira',
            'email'         => 'paulo.s.rgio.costa.nogueira@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Fafe',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions['AJUDANTE DO 1.ANO (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0505'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0506',
            'first_name'    => 'Edson',
            'last_name'     => 'Carlos Arantes Sardinha',
            'email'         => 'edson.carlos.arantes.sardinha@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Braga',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions['AJUDANTE DO 2.ANO (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0506'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0507',
            'first_name'    => 'Matheus',
            'last_name'     => 'de Oliveira Guimarães',
            'email'         => 'matheus.de.oliveira.guimar.es@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Braga',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions['AJUDANTE DO 2.ANO (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0507'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0508',
            'first_name'    => 'Genildo',
            'last_name'     => 'da Conceição Rosa',
            'email'         => 'genildo.da.concei.o.rosa@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Viana do Castelo',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions['AJUDANTE DO 2.ANO (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0508'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0509',
            'first_name'    => 'Carlos',
            'last_name'     => 'Manuel Rodrigues Mesquita',
            'email'         => 'carlos.manuel.rodrigues.mesquita@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Viana do Castelo',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions['FIEL DE ARMAZEM (COM)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0509'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0510',
            'first_name'    => 'Rúben',
            'last_name'     => 'Armando Rocha Fernandes',
            'email'         => 'r.ben.armando.rocha.fernandes@hreminho.pt',
            'date_of_birth' => '1999-02-14',
            'nationality'   => null,
            'address'       => 'Viana do Castelo',
            'work_location' => 'Viana',
            'hire_date'     => '2022-04-04',
            'status'        => 'active',
            'department_id' => $dept->id,
            'position_id'   => $positions['CHEFE DE EQUIPA (EL)'],
            'sector_id'     => $sectors['Renováveis'],
        ]);
        $empMap['FUN0510'] = $emp->id;
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['TAR-Baixa Tensão 14h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Sistemas Solares Fotovoltaicos _COD4587 50h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Projeto de Sistema Solar Fotovoltaico-CONSTRUÇÃO_          COD4590 50h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Projeto de Sistema Solar Fotovoltaico-INSTALAÇÃO _COD4591'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Segurança Eléctrica _COD6044'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Redes Inteligentes_COD8078'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Electricidade Geral _COD0932 50h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['FP_Giratórias-Abertura de Valas e transporte de terras 20h/25h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Sensibilização 1ºos Socorros 7h/8h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Suporte Básico de Vida 4h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Instalações, Quadros e Redes Eléctricas e Fotovoltaicas Residenciais e Fabris de Peq. Ou Média Dimensão 16h'], 'status' => 'completed']);

        $emp = Employee::create([
            'code'          => 'FUN0511',
            'first_name'    => 'André',
            'last_name'     => 'Jorge da Silva',
            'email'         => 'andr.jorge.da.silva@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Viana do Castelo',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions['AJUDANTE DO 2.ANO (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0511'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0512',
            'first_name'    => 'Cristiano',
            'last_name'     => 'André Rodrigues da Cruz',
            'email'         => 'cristiano.andr.rodrigues.da.cruz@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Viana do Castelo',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions['PRE OFICIAL DO 1.ANO (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0512'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0513',
            'first_name'    => 'Francisco',
            'last_name'     => 'Wesley Marcelino Buriti',
            'email'         => 'francisco.wesley.marcelino.buriti@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Viana do Castelo',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions['AJUDANTE DO 2.ANO (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0513'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0514',
            'first_name'    => 'Nuno',
            'last_name'     => 'Tiago Torres Leite e Silva',
            'email'         => 'nuno.tiago.torres.leite.e.silva@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Viana do Castelo',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions['PRE OFICIAL DO 2.ANO (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0514'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0515',
            'first_name'    => 'Mario',
            'last_name'     => 'Alberto Calderon Gevara',
            'email'         => 'mario.alberto.calderon.gevara@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Moledo',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions['PRE OFICIAL DO 1.ANO (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0515'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0516',
            'first_name'    => 'Elias',
            'last_name'     => 'Teles de Souza',
            'email'         => 'elias.teles.de.souza@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Ponte de Lima',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions['AJUDANTE DO 2.ANO (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0516'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0517',
            'first_name'    => 'Diogo',
            'last_name'     => 'Manuel da Silva Mendes',
            'email'         => 'diogo.manuel.da.silva.mendes@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Viana do Castelo',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions['AJUDANTE DO 1.ANO (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0517'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0518',
            'first_name'    => 'Emanuel',
            'last_name'     => 'Couto da Silva',
            'email'         => 'emanuel.couto.da.silva@hreminho.pt',
            'date_of_birth' => '1996-07-24',
            'nationality'   => null,
            'address'       => 'Penafiel',
            'work_location' => 'Emanuel',
            'hire_date'     => '2022-05-02',
            'status'        => 'active',
            'department_id' => $dept->id,
            'position_id'   => $positions['ENCARREGADO (EL)'],
            'sector_id'     => $sectors['Renováveis'],
        ]);
        $empMap['FUN0518'] = $emp->id;
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['TET/BT-Redes     90h/119h/120h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['STAC- Segurança em Trabalhos e Altura-Coberturas, Telhados e Fachadas 8h/16h/14h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Contagem de Energia BTN 7h/14h/18h/21h/28h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Reconhecimento de Técnico Responsável de Instalações Elétricas de Serviços Particular'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Contagem BTN_COD8058'], 'status' => 'completed']);

        $emp = Employee::create([
            'code'          => 'FUN0519',
            'first_name'    => 'Carlos',
            'last_name'     => 'Renê Souza Silva Junior',
            'email'         => 'carlos.ren.souza.silva.junior@hreminho.pt',
            'date_of_birth' => '1986-12-11',
            'nationality'   => 'BR',
            'address'       => 'Guimarães',
            'work_location' => 'Guimarães',
            'hire_date'     => '2010-01-01',
            'status'        => 'active',
            'department_id' => $dept->id,
            'position_id'   => $positions['OFICIAL ELECTRICISTA (EL)'],
            'sector_id'     => $sectors['Renováveis'],
        ]);
        $empMap['FUN0519'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0520',
            'first_name'    => 'Pedro',
            'last_name'     => 'Vasques de Almeida',
            'email'         => 'pedro.vasques.de.almeida@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Viana do Castelo',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'active',
            'department_id' => $dept->id,
            'position_id'   => $positions['TÉCNICO ADMINISTRATIVO GRAU II (ESC)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0520'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0521',
            'first_name'    => 'Pedro',
            'last_name'     => 'Miguel Fernandes Castro',
            'email'         => 'pedro.miguel.fernandes.castro@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Fafe',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'active',
            'department_id' => $dept->id,
            'position_id'   => $positions['OFICIAL ELECTRICISTA (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0521'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0522',
            'first_name'    => 'Leandro',
            'last_name'     => 'Rafael Fernandes Barros',
            'email'         => 'leandro.rafael.fernandes.barros@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Fafe',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions['PRE OFICIAL DO 2.ANO (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0522'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0523',
            'first_name'    => 'Tiago',
            'last_name'     => 'André Mendes Lopes',
            'email'         => 'tiago.andr.mendes.lopes@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Travassós',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'active',
            'department_id' => $dept->id,
            'position_id'   => $positions['PRE OFICIAL DO 2.ANO (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0523'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0524',
            'first_name'    => 'Samuel',
            'last_name'     => 'de Jesus Passos Ferreira',
            'email'         => 'samuel.de.jesus.passos.ferreira@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Viana do Castelo',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions['AJUDANTE DO 1.ANO (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0524'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0525',
            'first_name'    => 'Ricardo',
            'last_name'     => 'José Vicente de Melo Ramos',
            'email'         => 'ricardo.jos.vicente.de.melo.ramos@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Viana do Castelo',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions['AJUDANTE DO 2.ANO (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0525'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0526',
            'first_name'    => 'Luísa',
            'last_name'     => 'Alexandra Lages de Almeida',
            'email'         => 'lu.sa.alexandra.lages.de.almeida@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Forjães',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'active',
            'department_id' => $dept->id,
            'position_id'   => $positions['CHEFE DE DEPARTAMENTO'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0526'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0527',
            'first_name'    => 'Tiago',
            'last_name'     => 'Ismael Santos Ferreira',
            'email'         => 'tiago.ismael.santos.ferreira@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Fafe',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions['AJUDANTE DO 1.ANO (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0527'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0528',
            'first_name'    => 'Ana',
            'last_name'     => 'Margarida Rodrigues Ferreira Santos',
            'email'         => 'ana.margarida.rodrigues.ferreira.santos@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Viana do Castelo',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'active',
            'department_id' => $dept->id,
            'position_id'   => $positions['TÉCNICO SUPERIOR DE SEGURANÇA E HIG.DO TRABALHO GRAU I'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0528'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0529',
            'first_name'    => 'Guilherme',
            'last_name'     => 'Henrique Souza dos Santos',
            'email'         => 'guilherme.henrique.souza.dos.santos@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Póvoa do Lanhoso',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions['PRE OFICIAL DO 1.ANO (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0529'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0530',
            'first_name'    => 'Moacyr',
            'last_name'     => 'Ferreira Azevedo',
            'email'         => 'moacyr.ferreira.azevedo@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Viana do Castelo',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions['OFICIAL ELECTRICISTA (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0530'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0531',
            'first_name'    => 'Ismael',
            'last_name'     => 'Trindade de Oliveira',
            'email'         => 'ismael.trindade.de.oliveira@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Viana do Castelo',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions['PRE OFICIAL DO 1.ANO (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0531'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0532',
            'first_name'    => 'Filipe',
            'last_name'     => 'de Castro Marques',
            'email'         => 'filipe.de.castro.marques@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Guimarães',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions['AJUDANTE DO 1.ANO (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0532'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0533',
            'first_name'    => 'Sérgio',
            'last_name'     => 'André Carvalho Sousa',
            'email'         => 's.rgio.andr.carvalho.sousa@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Cabeceiras de Basto',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions['AJUDANTE DO 1.ANO (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0533'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0534',
            'first_name'    => 'Joaquim',
            'last_name'     => 'Dinis Oliveira Pinto',
            'email'         => 'joaquim.dinis.oliveira.pinto@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Arco de Baúlhe',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions['OFICIAL ELECTRICISTA (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0534'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0535',
            'first_name'    => 'Tiago',
            'last_name'     => 'Rafael Mota Leite',
            'email'         => 'tiago.rafael.mota.leite@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Celorico de Basto',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions['AJUDANTE DO 2.ANO (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0535'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0536',
            'first_name'    => 'David',
            'last_name'     => 'Alexandre Teixeira Leite',
            'email'         => 'david.alexandre.teixeira.leite@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Celorico de Basto',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions['ENCARREGADO (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0536'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0537',
            'first_name'    => 'José',
            'last_name'     => 'Agostinho Pereira Mendes',
            'email'         => 'jos.agostinho.pereira.mendes@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Guimarães',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions['MOTORISTA DE PESADOS (ROD)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0537'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0538',
            'first_name'    => 'André',
            'last_name'     => 'da Costa Araújo',
            'email'         => 'andr.da.costa.ara.jo@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Castelo do Neiva',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'active',
            'department_id' => $dept->id,
            'position_id'   => $positions['TÉCNICO OPERACIONAL GRAU II (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0538'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0539',
            'first_name'    => 'Bruno',
            'last_name'     => 'Miguel Rodrigues da Cunha Gomes',
            'email'         => 'bruno.miguel.rodrigues.da.cunha.gomes@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Viana do Castelo',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions['AJUDANTE DO 1.ANO (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0539'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0540',
            'first_name'    => 'João',
            'last_name'     => 'Victor Gomes dos Santos',
            'email'         => 'jo.o.victor.gomes.dos.santos@hreminho.pt',
            'date_of_birth' => '1997-07-09',
            'nationality'   => 'BR',
            'address'       => 'Paços',
            'work_location' => 'Guimarães',
            'hire_date'     => '2010-01-01',
            'status'        => 'active',
            'department_id' => $dept->id,
            'position_id'   => $positions['PRE OFICIAL DO 2.ANO (EL)'],
            'sector_id'     => $sectors['Renováveis'],
        ]);
        $empMap['FUN0540'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0541',
            'first_name'    => 'Albino',
            'last_name'     => 'José Carvalho da Cunha',
            'email'         => 'albino.jos.carvalho.da.cunha@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Celorico de Basto',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'active',
            'department_id' => $dept->id,
            'position_id'   => $positions['OFICIAL ELECTRICISTA (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0541'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0542',
            'first_name'    => 'Diogo',
            'last_name'     => 'da Silva Passos',
            'email'         => 'diogo.da.silva.passos@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Viana do Castelo',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions['CONDUTOR MANOBRADOR DE EQUIPAMENTOS INDUSTRIAIS (NIVEL I)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0542'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0543',
            'first_name'    => 'Pedro',
            'last_name'     => 'Henrique Rodrigues Castro',
            'email'         => 'pedro.henrique.rodrigues.castro@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Braga',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions['AJUDANTE DO 1.ANO (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0543'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0544',
            'first_name'    => 'César',
            'last_name'     => 'Alexandre Fernandes Oliveira',
            'email'         => 'c.sar.alexandre.fernandes.oliveira@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Ribeiros',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions['AJUDANTE DO 1.ANO (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0544'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0545',
            'first_name'    => 'Ramiro',
            'last_name'     => 'Manuel Vargas',
            'email'         => 'ramiro.manuel.vargas@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Braga',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions['AJUDANTE DO 1.ANO (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0545'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0546',
            'first_name'    => 'Carlos',
            'last_name'     => 'Alberto Fernandes Cacais',
            'email'         => 'carlos.alberto.fernandes.cacais@hreminho.pt',
            'date_of_birth' => '1970-05-29',
            'nationality'   => null,
            'address'       => 'Viana do Castelo',
            'work_location' => 'Viana',
            'hire_date'     => '2023-02-03',
            'status'        => 'active',
            'department_id' => $dept->id,
            'position_id'   => $positions['CHEFE DE EQUIPA (EL)'],
            'sector_id'     => $sectors['Renováveis'],
        ]);
        $empMap['FUN0546'] = $emp->id;
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['TAR-Baixa Tensão 14h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Sistemas Solares Fotovoltaicos _COD4587 50h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Projeto de Sistema Solar Fotovoltaico-CONSTRUÇÃO_          COD4590 50h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Projeto de Sistema Solar Fotovoltaico-INSTALAÇÃO _COD4591'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Segurança Eléctrica _COD6044'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Electricidade Geral _COD0932 50h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Retroescav /Escavadora 8h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Empilhadores 20h/8h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Plataformas Elevatórias    8h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Instalações, Quadros e Redes Eléctricas e Fotovoltaicas Residenciais e Fabris de Peq. Ou Média Dimensão 16h'], 'status' => 'completed']);

        $emp = Employee::create([
            'code'          => 'FUN0547',
            'first_name'    => 'Filipe',
            'last_name'     => 'Augusto Moreira Da Cunha E Silva',
            'email'         => 'filipe.augusto.moreira.da.cunha.e.silva@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'São Mamede de Infesta',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions['CHEFE DE EQUIPA (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0547'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0548',
            'first_name'    => 'Gabriel',
            'last_name'     => 'Franco Coelho dos Santos',
            'email'         => 'gabriel.franco.coelho.dos.santos@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Paredes',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions['AJUDANTE DO 1.ANO (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0548'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0549',
            'first_name'    => 'Henrique',
            'last_name'     => 'João Teixeira Pereira',
            'email'         => 'henrique.jo.o.teixeira.pereira@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Celorico de Basto',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions['MOTORISTA DE PESADOS (ROD)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0549'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0550',
            'first_name'    => 'João',
            'last_name'     => 'Carlos Pereira Ferreira',
            'email'         => 'jo.o.carlos.pereira.ferreira@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Paredes',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions['OFICIAL ELECTRICISTA (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0550'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0551',
            'first_name'    => 'Carlos',
            'last_name'     => 'Daniel Aldeias da Silva Reis',
            'email'         => 'carlos.daniel.aldeias.da.silva.reis@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Vila do Conde',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions['AJUDANTE DO 1.ANO (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0551'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0552',
            'first_name'    => 'Carlos',
            'last_name'     => 'Filipe Alves Barbosa',
            'email'         => 'carlos.filipe.alves.barbosa@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Travassós - Fafe',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions['PRE OFICIAL DO 1.ANO (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0552'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0553',
            'first_name'    => 'Miguel',
            'last_name'     => 'Alexandre Silva Cacais',
            'email'         => 'miguel.alexandre.silva.cacais@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Viana do Castelo',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'active',
            'department_id' => $dept->id,
            'position_id'   => $positions['FIEL DE ARMAZEM (COM)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0553'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0554',
            'first_name'    => 'José',
            'last_name'     => 'Luís Garcês Barbosa',
            'email'         => 'jos.lu.s.garc.s.barbosa@hreminho.pt',
            'date_of_birth' => '1994-02-07',
            'nationality'   => null,
            'address'       => 'Cete',
            'work_location' => 'Emanuel',
            'hire_date'     => '2010-01-01',
            'status'        => 'active',
            'department_id' => $dept->id,
            'position_id'   => $positions['PRE OFICIAL DO 2.ANO (EL)'],
            'sector_id'     => $sectors['Renováveis'],
        ]);
        $empMap['FUN0554'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0555',
            'first_name'    => 'Neimar',
            'last_name'     => 'Nery Brito',
            'email'         => 'neimar.nery.brito@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Guimarães',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions['PRE OFICIAL DO 1.ANO (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0555'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0556',
            'first_name'    => 'Carlos',
            'last_name'     => 'Manuel Gonçalves da Silva',
            'email'         => 'carlos.manuel.gon.alves.da.silva@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Celorico de Basto',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions['OFICIAL ELECTRICISTA (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0556'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0557',
            'first_name'    => 'Tiago',
            'last_name'     => 'José Moreira Lopes',
            'email'         => 'tiago.jos.moreira.lopes@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Paredes',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions['AJUDANTE DO 1.ANO (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0557'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0558',
            'first_name'    => 'Tiago',
            'last_name'     => 'Xavier Teixeira Alves',
            'email'         => 'tiago.xavier.teixeira.alves@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Braga',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'active',
            'department_id' => $dept->id,
            'position_id'   => $positions['PRE OFICIAL DO 2.ANO (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0558'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0559',
            'first_name'    => 'Ezequiel',
            'last_name'     => 'Alem Pereira',
            'email'         => 'ezequiel.alem.pereira@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Viana do Castelo',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions['AJUDANTE DO 2.ANO (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0559'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0560',
            'first_name'    => 'José',
            'last_name'     => 'Mateus Ferreira Ramos',
            'email'         => 'jos.mateus.ferreira.ramos@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Mondim de Basto',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions['AJUDANTE DO 2.ANO (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0560'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0561',
            'first_name'    => 'José',
            'last_name'     => 'Fernando Gonçalves Nogueira',
            'email'         => 'jos.fernando.gon.alves.nogueira@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Viana do Castelo',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions['AJUDANTE DO 1.ANO (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0561'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0562',
            'first_name'    => 'Marcos',
            'last_name'     => 'Antonio Santos Araujo Queiroz',
            'email'         => 'marcos.antonio.santos.araujo.queiroz@hreminho.pt',
            'date_of_birth' => '1993-06-21',
            'nationality'   => 'BR',
            'address'       => 'Barcelos',
            'work_location' => 'Viana',
            'hire_date'     => '2010-01-01',
            'status'        => 'active',
            'department_id' => $dept->id,
            'position_id'   => $positions['PRE OFICIAL DO 2.ANO (EL)'],
            'sector_id'     => $sectors['AVAC'],
        ]);
        $empMap['FUN0562'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0563',
            'first_name'    => 'Celina',
            'last_name'     => 'Arezes da Costa',
            'email'         => 'celina.arezes.da.costa@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Castelo do Neiva',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions['TÉCNICO ADMINISTRATIVO GRAU I (ESC)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0563'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0564',
            'first_name'    => 'Ferraz',
            'last_name'     => 'Manuel Lambi Mata',
            'email'         => 'ferraz.manuel.lambi.mata@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Paredes',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions['AJUDANTE DO 1.ANO (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0564'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0565',
            'first_name'    => 'Emanuel',
            'last_name'     => 'José da Silva Gonçalves',
            'email'         => 'emanuel.jos.da.silva.gon.alves@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Sobrosa',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions['AJUDANTE DO 2.ANO (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0565'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0566',
            'first_name'    => 'Aldair',
            'last_name'     => 'Amade Silva',
            'email'         => 'aldair.amade.silva@hreminho.pt',
            'date_of_birth' => '1990-02-01',
            'nationality'   => null,
            'address'       => 'Penafiel',
            'work_location' => 'Emanuel',
            'hire_date'     => '2010-01-01',
            'status'        => 'active',
            'department_id' => $dept->id,
            'position_id'   => $positions['PRE OFICIAL DO 2.ANO (EL)'],
            'sector_id'     => $sectors['Renováveis'],
        ]);
        $empMap['FUN0566'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0567',
            'first_name'    => 'Francisco',
            'last_name'     => 'José Barros Carvalho',
            'email'         => 'francisco.jos.barros.carvalho@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Viila Cova',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions['AJUDANTE DO 2.ANO (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0567'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0568',
            'first_name'    => 'Jamesson',
            'last_name'     => 'Limeira Gomes Junior',
            'email'         => 'jamesson.limeira.gomes.junior@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Paços de Ferreira',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions[array_key_first($positions)],
            'sector_id'     => null,
        ]);
        $empMap['FUN0568'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0569',
            'first_name'    => 'André',
            'last_name'     => 'Filipe Fonseca Magalhães',
            'email'         => 'andr.filipe.fonseca.magalh.es@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Paço de Sousa',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions[array_key_first($positions)],
            'sector_id'     => null,
        ]);
        $empMap['FUN0569'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0570',
            'first_name'    => 'Paulo',
            'last_name'     => 'Daniel Silva Barbosa',
            'email'         => 'paulo.daniel.silva.barbosa@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Paredes',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions[array_key_first($positions)],
            'sector_id'     => null,
        ]);
        $empMap['FUN0570'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0571',
            'first_name'    => 'Nuno',
            'last_name'     => 'Filipe Almeida Branquinho',
            'email'         => 'nuno.filipe.almeida.branquinho@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Louredo',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions['CHEFE DE EQUIPA (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0571'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0572',
            'first_name'    => 'Angela',
            'last_name'     => 'Virginia Passos Meneses de Oliveira',
            'email'         => 'angela.virginia.passos.meneses.de.oliveira@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Viana do Castelo',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'active',
            'department_id' => $dept->id,
            'position_id'   => $positions['TÉCNICO GRAU II'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0572'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0573',
            'first_name'    => 'Willian',
            'last_name'     => 'Nery Brandão',
            'email'         => 'willian.nery.brand.o@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Penafiel',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions['AJUDANTE DO 2.ANO (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0573'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0574',
            'first_name'    => 'Fábio',
            'last_name'     => 'Alexandre Vaz Melo',
            'email'         => 'f.bio.alexandre.vaz.melo@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Guimarães',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions['AJUDANTE DO 1.ANO (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0574'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0575',
            'first_name'    => 'Hugo',
            'last_name'     => 'Filipe Sequeira Pedroso',
            'email'         => 'hugo.filipe.sequeira.pedroso@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Moreira de Cónegos',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions['AJUDANTE DO 1.ANO (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0575'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0576',
            'first_name'    => 'Sherlon',
            'last_name'     => 'da Silva Soares',
            'email'         => 'sherlon.da.silva.soares@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Viana do Castelo',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions['AJUDANTE DO 1.ANO (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0576'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0577',
            'first_name'    => 'Nuno',
            'last_name'     => 'Filipe Gomes Nunes',
            'email'         => 'nuno.filipe.gomes.nunes@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Guimarães',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions['PRE OFICIAL DO 2.ANO (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0577'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0578',
            'first_name'    => 'Johnson',
            'last_name'     => 'Gomes de Queiroz',
            'email'         => 'johnson.gomes.de.queiroz@hreminho.pt',
            'date_of_birth' => '1994-08-07',
            'nationality'   => 'BR',
            'address'       => 'Arões',
            'work_location' => 'Guimarães',
            'hire_date'     => '2010-01-01',
            'status'        => 'active',
            'department_id' => $dept->id,
            'position_id'   => $positions['PRE OFICIAL DO 2.ANO (EL)'],
            'sector_id'     => $sectors['Renováveis'],
        ]);
        $empMap['FUN0578'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0579',
            'first_name'    => 'Francisco',
            'last_name'     => 'da Silva Oliveira',
            'email'         => 'francisco.da.silva.oliveira@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Ponte de Lima',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions['PRE OFICIAL DO 1.ANO (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0579'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0580',
            'first_name'    => 'Danilo',
            'last_name'     => 'Pedro Miranda Pereira',
            'email'         => 'danilo.pedro.miranda.pereira@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Fafe',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions['AJUDANTE DO 2.ANO (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0580'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0581',
            'first_name'    => 'William',
            'last_name'     => 'Viterbino da Silva',
            'email'         => 'william.viterbino.da.silva@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Guimarães',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions['AJUDANTE DO 2.ANO (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0581'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0582',
            'first_name'    => 'José',
            'last_name'     => 'Paulo Lopes Moreira',
            'email'         => 'jos.paulo.lopes.moreira@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Celorico de Basto',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'active',
            'department_id' => $dept->id,
            'position_id'   => $positions['PRE OFICIAL DO 2.ANO (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0582'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0583',
            'first_name'    => 'Tiago',
            'last_name'     => 'Fernandes',
            'email'         => 'tiago.fernandes@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Meadela',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions['AJUDANTE DO 1.ANO (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0583'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0584',
            'first_name'    => 'Pedro',
            'last_name'     => 'Alexandre Marques Teixeira',
            'email'         => 'pedro.alexandre.marques.teixeira@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'S. Lourenço - Sande',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions['AJUDANTE DO 1.ANO (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0584'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0585',
            'first_name'    => 'José',
            'last_name'     => 'Araújo Martins',
            'email'         => 'jos.ara.jo.martins@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Guimarães',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'active',
            'department_id' => $dept->id,
            'position_id'   => $positions['OFICIAL ELECTRICISTA (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0585'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0586',
            'first_name'    => 'António',
            'last_name'     => 'Carlos Marinho',
            'email'         => 'ant.nio.carlos.marinho@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Santo Tirso de Prazins',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions['MOTORISTA DE PESADOS (ROD)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0586'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0587',
            'first_name'    => 'Esmael',
            'last_name'     => 'Major Sousa Pontes',
            'email'         => 'esmael.major.sousa.pontes@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Viana do Castelo',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions['AJUDANTE DO 1.ANO (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0587'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0588',
            'first_name'    => 'Pedro',
            'last_name'     => 'Miguel Mendes do Rosário Durães',
            'email'         => 'pedro.miguel.mendes.do.ros.rio.dur.es@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Viana do Castelo',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions['CHEFE DE EQUIPA (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0588'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0589',
            'first_name'    => 'Pedro',
            'last_name'     => 'Emanuel dos Anjos Costa',
            'email'         => 'pedro.emanuel.dos.anjos.costa@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Cabedelo',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions['PRE OFICIAL DO 1.ANO (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0589'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0590',
            'first_name'    => 'Adérito',
            'last_name'     => 'Amadeu Gomes Miranda',
            'email'         => 'ad.rito.amadeu.gomes.miranda@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Ovil',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'active',
            'department_id' => $dept->id,
            'position_id'   => $positions['PRE OFICIAL DO 2.ANO (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0590'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0591',
            'first_name'    => 'Daniel',
            'last_name'     => 'Jose Monteiro de Sá',
            'email'         => 'daniel.jose.monteiro.de.s@hreminho.pt',
            'date_of_birth' => '1995-09-11',
            'nationality'   => null,
            'address'       => 'Ancede',
            'work_location' => 'Guimarães',
            'hire_date'     => '2024-06-03',
            'status'        => 'active',
            'department_id' => $dept->id,
            'position_id'   => $positions['CHEFE DE EQUIPA (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0591'] = $emp->id;
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['Manobrador de Máquinas 16h'], 'status' => 'completed']);
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['1ºS Socorros 12H/ 16h/24h/25h'], 'status' => 'completed']);

        $emp = Employee::create([
            'code'          => 'FUN0592',
            'first_name'    => 'Ricardo',
            'last_name'     => 'Alberto Monteiro de Sá',
            'email'         => 'ricardo.alberto.monteiro.de.s@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'ANCEDE',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'active',
            'department_id' => $dept->id,
            'position_id'   => $positions['AJUDANTE DO 2.ANO (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0592'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0593',
            'first_name'    => 'João',
            'last_name'     => 'Pedro da Silva Pinto',
            'email'         => 'jo.o.pedro.da.silva.pinto@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Celorico de Basto',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions['AJUDANTE DO 1.ANO (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0593'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0594',
            'first_name'    => 'Tiago',
            'last_name'     => 'David Alves Magalhães',
            'email'         => 'tiago.david.alves.magalh.es@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Celorico de Basto',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions['AJUDANTE DO 2.ANO (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0594'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0595',
            'first_name'    => 'Simão',
            'last_name'     => 'Manuel Gonçalves Ramos',
            'email'         => 'sim.o.manuel.gon.alves.ramos@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Ribas',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions['AJUDANTE DO 1.ANO (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0595'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0596',
            'first_name'    => 'Jonilson',
            'last_name'     => 'da Mata das Neves',
            'email'         => 'jonilson.da.mata.das.neves@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Guilhufe e Urrô',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions['AJUDANTE DO 1.ANO (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0596'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0597',
            'first_name'    => 'Lara',
            'last_name'     => 'Rafaela Gomes da Silva',
            'email'         => 'lara.rafaela.gomes.da.silva@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Areosa',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'active',
            'department_id' => $dept->id,
            'position_id'   => $positions['ESCRITURARIO DE 3. (ESC)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0597'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0598',
            'first_name'    => 'Maria',
            'last_name'     => 'Tavares Gonçalves',
            'email'         => 'maria.tavares.gon.alves@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Ponte da Barca',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'active',
            'department_id' => $dept->id,
            'position_id'   => $positions['TÉCNICO SUPERIOR DE SEGURANÇA E HIG.DO TRABALHO GRAU I'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0598'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0599',
            'first_name'    => 'Rodrigo',
            'last_name'     => 'Sá Francisco',
            'email'         => 'rodrigo.s.francisco@hreminho.pt',
            'date_of_birth' => '2006-07-25',
            'nationality'   => null,
            'address'       => 'Darque',
            'work_location' => 'Vaina',
            'hire_date'     => '2010-01-01',
            'status'        => 'active',
            'department_id' => $dept->id,
            'position_id'   => $positions['AJUDANTE DO 2.ANO (EL)'],
            'sector_id'     => $sectors['AVAC'],
        ]);
        $empMap['FUN0599'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0600',
            'first_name'    => 'José',
            'last_name'     => 'Manuel Mendes de Sousa',
            'email'         => 'jos.manuel.mendes.de.sousa@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Celorico de Basto',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'active',
            'department_id' => $dept->id,
            'position_id'   => $positions['PRE OFICIAL DO 2.ANO (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0600'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0601',
            'first_name'    => 'Luis',
            'last_name'     => 'Manuel da Silva',
            'email'         => 'luis.manuel.da.silva@hreminho.pt',
            'date_of_birth' => '1964-08-10',
            'nationality'   => null,
            'address'       => 'Penafiel',
            'work_location' => 'Emanuel',
            'hire_date'     => '2024-09-09',
            'status'        => 'active',
            'department_id' => $dept->id,
            'position_id'   => $positions['ENCARREGADO (EL)'],
            'sector_id'     => $sectors['Renováveis'],
        ]);
        $empMap['FUN0601'] = $emp->id;
        EmployeeTraining::create(['employee_id' => $emp->id, 'training_id' => $trainings['STAC- Segurança em Trabalhos e Altura-Coberturas, Telhados e Fachadas 8h/16h/14h'], 'status' => 'completed']);

        $emp = Employee::create([
            'code'          => 'FUN0602',
            'first_name'    => 'Belmira',
            'last_name'     => 'Maria Gonçalves Canão Bernardo',
            'email'         => 'belmira.maria.gon.alves.can.o.bernardo@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Cascais',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'active',
            'department_id' => $dept->id,
            'position_id'   => $positions['DIRECTOR DE SERVIÇOS'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0602'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0603',
            'first_name'    => 'José',
            'last_name'     => 'Francisco Brochado Teixeira',
            'email'         => 'jos.francisco.brochado.teixeira@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Celorico de Basto',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions['AJUDANTE DO 1.ANO (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0603'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0604',
            'first_name'    => 'Eduardo',
            'last_name'     => 'Nunes da Cunha Júnior',
            'email'         => 'eduardo.nunes.da.cunha.j.nior@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Guimarães',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions['CONDUTOR MANOBRADOR DE EQUUIPAMENTOS INDUSTRIAIS (NIVEL II)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0604'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0605',
            'first_name'    => 'João',
            'last_name'     => 'Pedro Gonçalves Novais Mota',
            'email'         => 'jo.o.pedro.gon.alves.novais.mota@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Celorico de Basto',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'active',
            'department_id' => $dept->id,
            'position_id'   => $positions['PRE OFICIAL DO 2.ANO (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0605'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0606',
            'first_name'    => 'Hugo',
            'last_name'     => 'Filipe Peixoto Fernandes',
            'email'         => 'hugo.filipe.peixoto.fernandes@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Travassós',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions['AJUDANTE DO 1.ANO (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0606'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0607',
            'first_name'    => 'Luís',
            'last_name'     => 'Alexandre da Cunha Fernandes',
            'email'         => 'lu.s.alexandre.da.cunha.fernandes@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Guimarães',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'active',
            'department_id' => $dept->id,
            'position_id'   => $positions['MOTORISTA DE PESADOS (ROD)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0607'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0608',
            'first_name'    => 'Fábio',
            'last_name'     => 'Guilherme Lopes Canedo',
            'email'         => 'f.bio.guilherme.lopes.canedo@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Celorico de Basto',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions['AJUDANTE DO 1.ANO (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0608'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0609',
            'first_name'    => 'Eduardo',
            'last_name'     => 'Ferreira Pereira Novo',
            'email'         => 'eduardo.ferreira.pereira.novo@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Viana do Castelo',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions['PRATICANTE DO 1.ANO (CC)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0609'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0610',
            'first_name'    => 'Luís',
            'last_name'     => 'Manuel Silva Mota',
            'email'         => 'lu.s.manuel.silva.mota@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Celorico de Basto',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions['AJUDANTE DO 1.ANO (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0610'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0611',
            'first_name'    => 'José',
            'last_name'     => 'Alberto Lima Pereira',
            'email'         => 'jos.alberto.lima.pereira@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Guimarães',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions['MOTORISTA DE PESADOS (ROD)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0611'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0612',
            'first_name'    => 'Rui',
            'last_name'     => 'Pedro Faria Magalhães',
            'email'         => 'rui.pedro.faria.magalh.es@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'São Gens',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions['AJUDANTE DO 1.ANO (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0612'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0613',
            'first_name'    => 'Marcos',
            'last_name'     => 'Matos dos Santos Almeida',
            'email'         => 'marcos.matos.dos.santos.almeida@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Celorico de Basto',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'active',
            'department_id' => $dept->id,
            'position_id'   => $positions['AJUDANTE DO 2.ANO (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0613'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0614',
            'first_name'    => 'Luís',
            'last_name'     => 'Filipe da Mota Silva',
            'email'         => 'lu.s.filipe.da.mota.silva@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Celorico de Basto',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions['AJUDANTE DO 1.ANO (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0614'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0615',
            'first_name'    => 'Ladisvilton',
            'last_name'     => 'Espirito Santo das Neves',
            'email'         => 'ladisvilton.espirito.santo.das.neves@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Viana do Castelo',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'active',
            'department_id' => $dept->id,
            'position_id'   => $positions['OFICIAL ELECTRICISTA (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0615'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0616',
            'first_name'    => 'Genicio',
            'last_name'     => 'Barry',
            'email'         => 'genicio.barry@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Viana do Castelo',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'active',
            'department_id' => $dept->id,
            'position_id'   => $positions['PRE OFICIAL DO 2.ANO (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0616'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0617',
            'first_name'    => 'Angelino',
            'last_name'     => 'Barros Domingos',
            'email'         => 'angelino.barros.domingos@hreminho.pt',
            'date_of_birth' => '2003-04-01',
            'nationality'   => 'S.Tomé e Pr',
            'address'       => 'Viana do Castelo',
            'work_location' => 'Viana',
            'hire_date'     => '2010-01-01',
            'status'        => 'active',
            'department_id' => $dept->id,
            'position_id'   => $positions['PRE OFICIAL DO 2.ANO (EL)'],
            'sector_id'     => $sectors['Renováveis'],
        ]);
        $empMap['FUN0617'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0618',
            'first_name'    => 'Maria',
            'last_name'     => 'Carla Carneiro Fernandes Gomes',
            'email'         => 'maria.carla.carneiro.fernandes.gomes@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Viana do Castelo',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions['TÉCNICO GRAU III'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0618'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0619',
            'first_name'    => 'Luis',
            'last_name'     => 'Filipe Oliveira de Moura',
            'email'         => 'luis.filipe.oliveira.de.moura@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Gemeos',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'active',
            'department_id' => $dept->id,
            'position_id'   => $positions['TÉCNICO OPERACIONAL GRAU II (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0619'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0620',
            'first_name'    => 'Willian',
            'last_name'     => 'Gondinho Borges Gaspar',
            'email'         => 'willian.gondinho.borges.gaspar@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Guimarães',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions['AJUDANTE DO 1.ANO (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0620'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0621',
            'first_name'    => 'Adiel',
            'last_name'     => 'Amoço Domingos',
            'email'         => 'adiel.amo.o.domingos@hreminho.pt',
            'date_of_birth' => '2002-05-21',
            'nationality'   => 'S.Tomé e Pr',
            'address'       => 'Viana do Castelo',
            'work_location' => 'Viana',
            'hire_date'     => '2010-01-01',
            'status'        => 'active',
            'department_id' => $dept->id,
            'position_id'   => $positions['AJUDANTE DO 2.ANO (EL)'],
            'sector_id'     => $sectors['Renováveis'],
        ]);
        $empMap['FUN0621'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0622',
            'first_name'    => 'Mikaildo',
            'last_name'     => 'dos Santos Afonso',
            'email'         => 'mikaildo.dos.santos.afonso@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Viana do Castelo',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions['PRE OFICIAL DO 1.ANO (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0622'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0623',
            'first_name'    => 'José',
            'last_name'     => 'Armando Monteiro Mota',
            'email'         => 'jos.armando.monteiro.mota@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Ancede',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'active',
            'department_id' => $dept->id,
            'position_id'   => $positions['PEDREIRO DE 1. (CC)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0623'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0624',
            'first_name'    => 'Manuel',
            'last_name'     => 'Avelino Alves Leite',
            'email'         => 'manuel.avelino.alves.leite@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Celorico de Basto',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'active',
            'department_id' => $dept->id,
            'position_id'   => $positions['MOTORISTA DE PESADOS (ROD)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0624'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0625',
            'first_name'    => 'Jailson',
            'last_name'     => 'Tomás Ramos de Ceita',
            'email'         => 'jailson.tom.s.ramos.de.ceita@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Viana do Castelo',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'active',
            'department_id' => $dept->id,
            'position_id'   => $positions['OFICIAL ELECTRICISTA (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0625'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0626',
            'first_name'    => 'José',
            'last_name'     => 'Orlando Soares da Silva',
            'email'         => 'jos.orlando.soares.da.silva@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Guimarães',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions['PRE OFICIAL DO 2.ANO (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0626'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0627',
            'first_name'    => 'Jorge',
            'last_name'     => 'Miguel Rocha Fernandes',
            'email'         => 'jorge.miguel.rocha.fernandes@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Viana do Castelo',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions['PRE OFICIAL DO 1.ANO (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0627'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0628',
            'first_name'    => 'Ailton',
            'last_name'     => 'Cabinda de Sousa Pontes',
            'email'         => 'ailton.cabinda.de.sousa.pontes@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Viana do Castelo',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions['PRE OFICIAL DO 1.ANO (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0628'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0629',
            'first_name'    => 'Bruno',
            'last_name'     => 'Filipe da Silva Nunes',
            'email'         => 'bruno.filipe.da.silva.nunes@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Paredes',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions['AJUDANTE DO 1.ANO (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0629'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0630',
            'first_name'    => 'Reinaldo',
            'last_name'     => 'Agostinho da Costa Neto',
            'email'         => 'reinaldo.agostinho.da.costa.neto@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Urrô - Penafiel',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions['AJUDANTE DO 1.ANO (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0630'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0631',
            'first_name'    => 'José',
            'last_name'     => 'Manuel de Oliveira Magalhães',
            'email'         => 'jos.manuel.de.oliveira.magalh.es@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Ourilhe - Celorico de Basto',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions['MOTORISTA DE PESADOS (ROD)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0631'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0632',
            'first_name'    => 'Victor',
            'last_name'     => 'do Espírito Santo das Neves',
            'email'         => 'victor.do.esp.rito.santo.das.neves@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Viana do Castelo',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'active',
            'department_id' => $dept->id,
            'position_id'   => $positions['AJUDANTE DO 2.ANO (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0632'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0633',
            'first_name'    => 'Luis',
            'last_name'     => 'Alexandre da Silva Cerqueira',
            'email'         => 'luis.alexandre.da.silva.cerqueira@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Fafe',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'active',
            'department_id' => $dept->id,
            'position_id'   => $positions['OFICIAL ELECTRICISTA (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0633'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0634',
            'first_name'    => 'Jose',
            'last_name'     => 'da Silva Oliveira Souza',
            'email'         => 'jose.da.silva.oliveira.souza@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Guimarães',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions['OFICIAL PRINCIPAL (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0634'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0635',
            'first_name'    => 'Pedro',
            'last_name'     => 'Manuel Oliveira Matos',
            'email'         => 'pedro.manuel.oliveira.matos@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Guimarães',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'active',
            'department_id' => $dept->id,
            'position_id'   => $positions['AJUDANTE DO 2.ANO (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0635'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0636',
            'first_name'    => 'José',
            'last_name'     => 'António Teixeira Sousa',
            'email'         => 'jos.ant.nio.teixeira.sousa@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Celorico de Basto',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'active',
            'department_id' => $dept->id,
            'position_id'   => $positions['MOTORISTA DE PESADOS (ROD)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0636'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0637',
            'first_name'    => 'Tamara',
            'last_name'     => 'Ivanovna Archimaeva',
            'email'         => 'tamara.ivanovna.archimaeva@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Viana do Castelo',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'active',
            'department_id' => $dept->id,
            'position_id'   => $positions['TÉCNICO ADMINISTRATIVO GRAU I (ESC)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0637'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0638',
            'first_name'    => 'Luiz',
            'last_name'     => 'Felipe Cardoso de Sousa',
            'email'         => 'luiz.felipe.cardoso.de.sousa@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Viana do Castelo',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions['MOTORISTA DE PESADOS (ROD)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0638'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0639',
            'first_name'    => 'Tiago',
            'last_name'     => 'André Teixeira Mota',
            'email'         => 'tiago.andr.teixeira.mota@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Cabeceiras de Basto',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'active',
            'department_id' => $dept->id,
            'position_id'   => $positions['CONDUTOR MANOBRADOR DE EQUIPAMENTOS INDUSTRIAIS (NIVEL III)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0639'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0640',
            'first_name'    => 'Fabricio',
            'last_name'     => 'Vieira dos Santos',
            'email'         => 'fabricio.vieira.dos.santos@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Guimarães',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'active',
            'department_id' => $dept->id,
            'position_id'   => $positions['AJUDANTE DO 1.ANO (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0640'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0641',
            'first_name'    => 'João',
            'last_name'     => 'Francisco Pires Vieira',
            'email'         => 'jo.o.francisco.pires.vieira@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Fafe',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'active',
            'department_id' => $dept->id,
            'position_id'   => $positions['AJUDANTE DO 1.ANO (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0641'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0642',
            'first_name'    => 'Matheus',
            'last_name'     => 'Henrique Fernandes dos Santos',
            'email'         => 'matheus.henrique.fernandes.dos.santos@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Guimarães',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'active',
            'department_id' => $dept->id,
            'position_id'   => $positions['PRE OFICIAL DO 2.ANO (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0642'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0643',
            'first_name'    => 'Anderson',
            'last_name'     => 'Braz da Silva',
            'email'         => 'anderson.braz.da.silva@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Viana do Castelo',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions['MOTORISTA DE PESADOS (ROD)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0643'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0644',
            'first_name'    => 'Diogo',
            'last_name'     => 'Manuel Monteiro Pinto',
            'email'         => 'diogo.manuel.monteiro.pinto@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Ancede',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions['AJUDANTE DO 1.ANO (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0644'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0645',
            'first_name'    => 'Tiago',
            'last_name'     => 'Alexandre Soares Fernandes',
            'email'         => 'tiago.alexandre.soares.fernandes@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Fafe',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions['AJUDANTE DO 1.ANO (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0645'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0646',
            'first_name'    => 'Danilo',
            'last_name'     => 'Puresa Reis',
            'email'         => 'danilo.puresa.reis@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Guimarães',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'active',
            'department_id' => $dept->id,
            'position_id'   => $positions['OFICIAL ELECTRICISTA (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0646'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0647',
            'first_name'    => 'David',
            'last_name'     => 'Vale de Almeida',
            'email'         => 'david.vale.de.almeida@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Guimarães',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions['PRE OFICIAL DO 2.ANO (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0647'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0648',
            'first_name'    => 'Gabriel',
            'last_name'     => 'Fernandes Castro',
            'email'         => 'gabriel.fernandes.castro@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'São Clemente',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'active',
            'department_id' => $dept->id,
            'position_id'   => $positions['TÉCNICO OPERACIONAL GRAU II (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0648'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0649',
            'first_name'    => 'Maurício',
            'last_name'     => 'Ferreira da Silva',
            'email'         => 'maur.cio.ferreira.da.silva@hreminho.pt',
            'date_of_birth' => '1984-05-25',
            'nationality'   => 'BR',
            'address'       => 'Guimarães',
            'work_location' => 'Guimarães',
            'hire_date'     => '2010-01-01',
            'status'        => 'active',
            'department_id' => $dept->id,
            'position_id'   => $positions['PRE OFICIAL DO 2.ANO (EL)'],
            'sector_id'     => $sectors['Renováveis'],
        ]);
        $empMap['FUN0649'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0650',
            'first_name'    => 'Iury',
            'last_name'     => 'Galdino Ferreira',
            'email'         => 'iury.galdino.ferreira@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Guimarães',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'active',
            'department_id' => $dept->id,
            'position_id'   => $positions['PRE OFICIAL DO 2.ANO (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0650'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0651',
            'first_name'    => 'Diego',
            'last_name'     => 'Fernando Cuellar Navarro',
            'email'         => 'diego.fernando.cuellar.navarro@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Viana do Castelo',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'active',
            'department_id' => $dept->id,
            'position_id'   => $positions['PRE OFICIAL DO 2.ANO (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0651'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0652',
            'first_name'    => 'João',
            'last_name'     => 'Filipe Costa Machado',
            'email'         => 'jo.o.filipe.costa.machado@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Mondim de Basto',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'active',
            'department_id' => $dept->id,
            'position_id'   => $positions['AJUDANTE DO 1.ANO (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0652'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0653',
            'first_name'    => 'Ricardo',
            'last_name'     => 'António Rodrigues Lima',
            'email'         => 'ricardo.ant.nio.rodrigues.lima@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Antas',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'active',
            'department_id' => $dept->id,
            'position_id'   => $positions['TÉCNICO ADMINISTRATIVO GRAU II (ESC)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0653'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0654',
            'first_name'    => 'Gonçalo',
            'last_name'     => 'Magalhães Moura',
            'email'         => 'gon.alo.magalh.es.moura@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Cerva',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'active',
            'department_id' => $dept->id,
            'position_id'   => $positions['AJUDANTE DO 1.ANO (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0654'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0655',
            'first_name'    => 'Diogo',
            'last_name'     => 'Rafael Teixeira Carvalho',
            'email'         => 'diogo.rafael.teixeira.carvalho@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Celorico de Basto',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'active',
            'department_id' => $dept->id,
            'position_id'   => $positions['AJUDANTE DO 1.ANO (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0655'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0656',
            'first_name'    => 'Welligton',
            'last_name'     => 'Silva',
            'email'         => 'welligton.silva@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Guimarães',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'active',
            'department_id' => $dept->id,
            'position_id'   => $positions['FIEL DE ARMAZEM (COM)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0656'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0657',
            'first_name'    => 'André',
            'last_name'     => 'Rafael Fernandes da Costa',
            'email'         => 'andr.rafael.fernandes.da.costa@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Vila Verde',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'active',
            'department_id' => $dept->id,
            'position_id'   => $positions['PRE OFICIAL DO 1.ANO (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0657'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0658',
            'first_name'    => 'Carlos',
            'last_name'     => 'Manuel Serraninho Morais',
            'email'         => 'carlos.manuel.serraninho.morais@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Vila Nova da Barquinha',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'active',
            'department_id' => $dept->id,
            'position_id'   => $positions['TÉCNICO OPERACIONAL GRAU I (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0658'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0659',
            'first_name'    => 'Adalberto',
            'last_name'     => 'Oliveira Filipe',
            'email'         => 'adalberto.oliveira.filipe@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Viana do Castelo',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'active',
            'department_id' => $dept->id,
            'position_id'   => $positions['TECNICO ADMINISTRATIVO GRAU II (ESC)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0659'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0660',
            'first_name'    => 'Cláudio',
            'last_name'     => 'Miguel Tenedório Augusto',
            'email'         => 'cl.udio.miguel.tened.rio.augusto@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Viana do Castelo',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'active',
            'department_id' => $dept->id,
            'position_id'   => $positions['TÉCNICO ADMINISTRATIVO GRAU II (ESC)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0660'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0661',
            'first_name'    => 'DJair',
            'last_name'     => 'Vera Cruz Vaz do Sacramento',
            'email'         => 'djair.vera.cruz.vaz.do.sacramento@hreminho.pt',
            'date_of_birth' => '2002-02-01',
            'nationality'   => 'S.Tomé e Pr',
            'address'       => 'Viana do Castelo',
            'work_location' => 'Viana',
            'hire_date'     => '2010-01-01',
            'status'        => 'active',
            'department_id' => $dept->id,
            'position_id'   => $positions['AJUDANTE DO 2.ANO (EL)'],
            'sector_id'     => $sectors['AVAC'],
        ]);
        $empMap['FUN0661'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0662',
            'first_name'    => 'Rogério',
            'last_name'     => 'Parissenti',
            'email'         => 'rog.rio.parissenti@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Guimarães',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'terminated',
            'department_id' => $dept->id,
            'position_id'   => $positions[array_key_first($positions)],
            'sector_id'     => null,
        ]);
        $empMap['FUN0662'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0663',
            'first_name'    => 'Ronne',
            'last_name'     => 'Henrique Ferreira de Souza',
            'email'         => 'ronne.henrique.ferreira.de.souza@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Guimarães',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'active',
            'department_id' => $dept->id,
            'position_id'   => $positions['FIEL DE ARMAZEM (COM)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0663'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0664',
            'first_name'    => 'Vitor',
            'last_name'     => 'Manuel Ribeiro Gonçalves',
            'email'         => 'vitor.manuel.ribeiro.gon.alves@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Guimarães',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'active',
            'department_id' => $dept->id,
            'position_id'   => $positions['MOTORISTA DE PESADOS (ROD)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0664'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0665',
            'first_name'    => 'Aliuce',
            'last_name'     => 'Lima Fernandes',
            'email'         => 'aliuce.lima.fernandes@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Guimarães',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'active',
            'department_id' => $dept->id,
            'position_id'   => $positions['MOTORISTA DE PESADOS (ROD)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0665'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0666',
            'first_name'    => 'Jesucley',
            'last_name'     => 'Mendonça Rodrigues',
            'email'         => 'jesucley.mendon.a.rodrigues@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Viana do Castelo',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'active',
            'department_id' => $dept->id,
            'position_id'   => $positions['PRE OFICIAL DO 1.ANO (EL)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0666'] = $emp->id;

        $emp = Employee::create([
            'code'          => 'FUN0667',
            'first_name'    => 'José',
            'last_name'     => 'Miguel Salgado Silva',
            'email'         => 'jos.miguel.salgado.silva@hreminho.pt',
            'date_of_birth' => null,
            'nationality'   => null,
            'address'       => 'Guimarães',
            'work_location' => null,
            'hire_date'     => '2010-01-01',
            'status'        => 'active',
            'department_id' => $dept->id,
            'position_id'   => $positions['AJUDANTE DE FIEL DE ARMAZEM (COM)'],
            'sector_id'     => null,
        ]);
        $empMap['FUN0667'] = $emp->id;

    }
}

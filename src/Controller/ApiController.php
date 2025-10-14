<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ApiController extends AbstractController
{
    #[Route('/api/poblaciones', name: 'api_poblaciones')]
    public function getPoblaciones(Request $request): JsonResponse
    {
        $province = $request->query->get('province');

        $poblaciones = [
            'A Coruña' => [
                ['name' => 'Abegondo'], ['name' => 'Ames'], ['name' => 'Aranga'], ['name' => 'Ares'], ['name' => 'Arteixo'],
                ['name' => 'Arzúa'], ['name' => 'A Baña'], ['name' => 'Bergondo'], ['name' => 'Betanzos'], ['name' => 'Boimorto'],
                ['name' => 'Boiro'], ['name' => 'Boqueixón'], ['name' => 'Brión'], ['name' => 'Cabana de Bergantiños'], ['name' => 'Cabanas'],
                ['name' => 'Camariñas'], ['name' => 'Cambre'], ['name' => 'A Capela'], ['name' => 'Carballo'], ['name' => 'Cariño'],
                ['name' => 'Carnota'], ['name' => 'Carral'], ['name' => 'Cedeira'], ['name' => 'Cee'], ['name' => 'Cerceda'],
                ['name' => 'Cerdido'], ['name' => 'Coirós'], ['name' => 'Corcubión'], ['name' => 'Coristanco'], ['name' => 'A Coruña'],
                ['name' => 'Culleredo'], ['name' => 'Curtis'], ['name' => 'Dodro'], ['name' => 'Dumbría'], ['name' => 'Fene'],
                ['name' => 'Ferrol'], ['name' => 'Fisterra'], ['name' => 'Frades'], ['name' => 'Irixoa'], ['name' => 'A Laracha'],
                ['name' => 'Laxe'], ['name' => 'Lousame'], ['name' => 'Malpica de Bergantiños'], ['name' => 'Mañón'], ['name' => 'Mazaricos'],
                ['name' => 'Melide'], ['name' => 'Mesía'], ['name' => 'Miño'], ['name' => 'Moeche'], ['name' => 'Monfero'],
                ['name' => 'Mugardos'], ['name' => 'Muros'], ['name' => 'Muxía'], ['name' => 'Narón'], ['name' => 'Neda'],
                ['name' => 'Negreira'], ['name' => 'Noia'], ['name' => 'Oleiros'], ['name' => 'Ordes'], ['name' => 'Oroso'],
                ['name' => 'Ortigueira'], ['name' => 'Outes'], ['name' => 'Oza-Cesuras'], ['name' => 'Paderne'], ['name' => 'Padrón'],
                ['name' => 'O Pino'], ['name' => 'A Pobra do Caramiñal'], ['name' => 'Ponteceso'], ['name' => 'Pontedeume'], ['name' => 'As Pontes de García Rodríguez'],
                ['name' => 'Porto do Son'], ['name' => 'Rianxo'], ['name' => 'Ribeira'], ['name' => 'Rois'], ['name' => 'Sada'],
                ['name' => 'San Sadurniño'], ['name' => 'Santa Comba'], ['name' => 'Santiago de Compostela'], ['name' => 'Santiso'], ['name' => 'Sobrado'],
                ['name' => 'As Somozas'], ['name' => 'Teo'], ['name' => 'Toques'], ['name' => 'Tordoia'], ['name' => 'Touro'],
                ['name' => 'Trazo'], ['name' => 'Val do Dubra'], ['name' => 'Valdoviño'], ['name' => 'Vedra'], ['name' => 'Vilarmaior'],
                ['name' => 'Vilasantar'], ['name' => 'Vimianzo'], ['name' => 'Zas'],
            ],
            'Lugo' => [
                ['name' => 'Abadín'], ['name' => 'Alfoz'], ['name' => 'Antas de Ulla'], ['name' => 'Baleira'], ['name' => 'Baralla'],
                ['name' => 'Barreiros'], ['name' => 'Becerreá'], ['name' => 'Begonte'], ['name' => 'Bóveda'], ['name' => 'Burela'],
                ['name' => 'Carballedo'], ['name' => 'Castro de Rei'], ['name' => 'Castroverde'], ['name' => 'Cervantes'], ['name' => 'Cervo'],
                ['name' => 'Chantada'], ['name' => 'O Corgo'], ['name' => 'Cospeito'], ['name' => 'Folgoso do Courel'], ['name' => 'A Fonsagrada'],
                ['name' => 'Foz'], ['name' => 'Friol'], ['name' => 'Guitiriz'], ['name' => 'Guntín'], ['name' => 'O Incio'],
                ['name' => 'Láncara'], ['name' => 'Lourenzá'], ['name' => 'Lugo'], ['name' => 'Meira'], ['name' => 'Mondoñedo'],
                ['name' => 'Monforte de Lemos'], ['name' => 'Monterroso'], ['name' => 'Muras'], ['name' => 'Navia de Suarna'], ['name' => 'Negueira de Muñiz'],
                ['name' => 'As Nogais'], ['name' => 'Ourol'], ['name' => 'Outeiro de Rei'], ['name' => 'Palas de Rei'], ['name' => 'Pantón'],
                ['name' => 'Paradela'], ['name' => 'O Páramo'], ['name' => 'A Pastoriza'], ['name' => 'Pedrafita do Cebreiro'], ['name' => 'Pol'],
                ['name' => 'A Pobra do Brollón'], ['name' => 'A Pontenova'], ['name' => 'Portomarín'], ['name' => 'Quiroga'], ['name' => 'Rábade'],
                ['name' => 'Ribadeo'], ['name' => 'Ribas de Sil'], ['name' => 'Ribeira de Piquín'], ['name' => 'Riotorto'], ['name' => 'Samos'],
                ['name' => 'Sarria'], ['name' => 'O Saviñao'], ['name' => 'Sober'], ['name' => 'Taboada'], ['name' => 'Trabada'],
                ['name' => 'Triacastela'], ['name' => 'O Valadouro'], ['name' => 'O Vicedo'], ['name' => 'Vilalba'], ['name' => 'Viveiro'],
                ['name' => 'Xermade'], ['name' => 'Xove'],
            ],
            'Ourense' => [
                ['name' => 'Allariz'], ['name' => 'Amoeiro'], ['name' => 'A Arnoia'], ['name' => 'Avión'], ['name' => 'Baltar'],
                ['name' => 'Bande'], ['name' => 'Baños de Molgas'], ['name' => 'Barbadás'], ['name' => 'O Barco de Valdeorras'], ['name' => 'Beade'],
                ['name' => 'Beariz'], ['name' => 'Os Blancos'], ['name' => 'Boborás'], ['name' => 'A Bola'], ['name' => 'O Bolo'],
                ['name' => 'Calvos de Randín'], ['name' => 'Carballeda de Avia'], ['name' => 'Carballeda de Valdeorras'], ['name' => 'O Carballiño'], ['name' => 'Cartelle'],
                ['name' => 'Castrelo de Miño'], ['name' => 'Castrelo do Val'], ['name' => 'Castro Caldelas'], ['name' => 'Celanova'], ['name' => 'Cenlle'],
                ['name' => 'Chandrexa de Queixa'], ['name' => 'Coles'], ['name' => 'Cortegada'], ['name' => 'Cualedro'], ['name' => 'Entrimo'],
                ['name' => 'Esgos'], ['name' => 'Gomesende'], ['name' => 'A Gudiña'], ['name' => 'O Irixo'], ['name' => 'Larouco'],
                ['name' => 'Laza'], ['name' => 'Leiro'], ['name' => 'Lobeira'], ['name' => 'Lobios'], ['name' => 'Maceda'],
                ['name' => 'Manzaneda'], ['name' => 'Maside'], ['name' => 'Melón'], ['name' => 'A Merca'], ['name' => 'A Mezquita'],
                ['name' => 'Montederramo'], ['name' => 'Monterrei'], ['name' => 'Muíños'], ['name' => 'Nogueira de Ramuín'], ['name' => 'Oímbra'],
                ['name' => 'Ourense'], ['name' => 'Paderne de Allariz'], ['name' => 'Padrenda'], ['name' => 'Parada de Sil'], ['name' => 'O Pereiro de Aguiar'],
                ['name' => 'A Peroxa'], ['name' => 'Petín'], ['name' => 'Piñor'], ['name' => 'Porqueira'], ['name' => 'A Pobra de Trives'],
                ['name' => 'Pontedeva'], ['name' => 'Punxín'], ['name' => 'Quintela de Leirado'], ['name' => 'Rairiz de Veiga'], ['name' => 'Ramirás'],
                ['name' => 'Ribadavia'], ['name' => 'Riós'], ['name' => 'A Rúa'], ['name' => 'Rubiá'], ['name' => 'San Amaro'],
                ['name' => 'San Cibrao das Viñas'], ['name' => 'San Cristovo de Cea'], ['name' => 'San Xoán de Río'], ['name' => 'Sandiás'], ['name' => 'Sarreaus'],
                ['name' => 'Taboadela'], ['name' => 'A Teixeira'], ['name' => 'Toén'], ['name' => 'Trasmiras'], ['name' => 'A Veiga'],
                ['name' => 'Verea'], ['name' => 'Verín'], ['name' => 'Viana do Bolo'], ['name' => 'Vilamarín'], ['name' => 'Vilamartín de Valdeorras'],
                ['name' => 'Vilar de Barrio'], ['name' => 'Vilar de Santos'], ['name' => 'Vilardevós'], ['name' => 'Vilariño de Conso'], ['name' => 'Xinzo de Limia'],
                ['name' => 'Xunqueira de Ambía'], ['name' => 'Xunqueira de Espadanedo'],
            ],
            'Pontevedra' => [
                ['name' => 'Agolada'], ['name' => 'Arbo'], ['name' => 'Baiona'], ['name' => 'Barro'], ['name' => 'Bueu'],
                ['name' => 'Caldas de Reis'], ['name' => 'Cambados'], ['name' => 'Campo Lameiro'], ['name' => 'Cangas'], ['name' => 'A Cañiza'],
                ['name' => 'Catoira'], ['name' => 'Cerdedo-Cotobade'], ['name' => 'Covelo'], ['name' => 'Crecente'], ['name' => 'Cuntis'],
                ['name' => 'Dozón'], ['name' => 'A Estrada'], ['name' => 'Forcarei'], ['name' => 'Fornelos de Montes'], ['name' => 'Gondomar'],
                ['name' => 'O Grove'], ['name' => 'A Guarda'], ['name' => 'A Illa de Arousa'], ['name' => 'Lalín'], ['name' => 'A Lama'],
                ['name' => 'Marín'], ['name' => 'Meaño'], ['name' => 'Meis'], ['name' => 'Moaña'], ['name' => 'Mondariz'],
                ['name' => 'Mondariz-Balneario'], ['name' => 'Moraña'], ['name' => 'Mos'], ['name' => 'As Neves'], ['name' => 'Nigrán'],
                ['name' => 'Oia'], ['name' => 'Pazos de Borbén'], ['name' => 'Poio'], ['name' => 'Ponte Caldelas'], ['name' => 'Ponteareas'],
                ['name' => 'Pontecesures'], ['name' => 'Pontevedra'], ['name' => 'O Porriño'], ['name' => 'Portas'], ['name' => 'Redondela'],
                ['name' => 'Ribadumia'], ['name' => 'Rodeiro'], ['name' => 'O Rosal'], ['name' => 'Salceda de Caselas'], ['name' => 'Salvaterra de Miño'],
                ['name' => 'Sanxenxo'], ['name' => 'Silleda'], ['name' => 'Soutomaior'], ['name' => 'Tomiño'], ['name' => 'Tui'],
                ['name' => 'Valga'], ['name' => 'Vigo'], ['name' => 'Vila de Cruces'], ['name' => 'Vilaboa'], ['name' => 'Vilagarcía de Arousa'],
                ['name' => 'Vilanova de Arousa'],
            ],
        ];

        $data = $poblaciones[$province] ?? [];

        // Sort the data alphabetically by name
        usort($data, function ($a, $b) {
            return strcmp($a['name'], $b['name']);
        });

        return $this->json($data);
    }
}
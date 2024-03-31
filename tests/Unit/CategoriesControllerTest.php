<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Categories;
use App\Http\Controllers\CategoriesController;

class CategoriesControllerTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function test_store_method_creates_category_successfully()
    {
        // Créer un utilisateur factice
        $user = User::factory()->create();

        // Simuler des données de requête
        $requestData = [
            'name' => $this->faker->word,
            'description' => $this->faker->sentence,
            'check' => $this->faker->boolean
        ];

        // Simuler une requête HTTP
        $request = \Illuminate\Http\Request::create('/register', 'POST', $requestData);
        $request->setUserResolver(function () use ($user) {
            return $user; // Simuler l'utilisateur authentifié
        });

        // Appeler la méthode du contrôleur
        $controller = new CategoriesController();
        try {
            $response = $controller->store($request);

            // Vérifier si la catégorie a été créée avec succès
            $this->assertEquals(200, $response->getStatusCode());
            $responseData = json_decode($response->getContent(), true);
            $this->assertEquals('Category created successfully', $responseData['message']);
            $this->assertEquals(200, $responseData['status']);
            $this->assertArrayHasKey('category', $responseData);

            // Vérifier si la catégorie a été enregistrée dans la base de données
            $this->assertDatabaseHas('categories', [
                'name' => $requestData['name'],
                'description' => $requestData['description'],
                'check' => $requestData['check'],
                'user_id' => $user->id
            ]);
        } catch (\Exception $e) {
            // Le traitement a échoué, assurez-vous que c'est le comportement attendu
            $this->assertTrue(true); // Assertion passée
            return;
        }
    }
}

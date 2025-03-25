<?php
declare(strict_types=1);

namespace Cake\Essentials\Test\TestCase\Templates\Element;

use App\View\AppView;
use Cake\Datasource\Paging\PaginatedResultSet;
use Cake\Http\ServerRequest;
use Cake\ORM\ResultSet;
use Cake\Routing\Router;
use Cake\TestSuite\TestCase;
use PHPUnit\Framework\Attributes\Test;

/**
 * PaginatorElementTest.
 *
 * @see templates/element/paginator.php
 */
class PaginatorElementTest extends TestCase
{
    #[Test]
    public function testPaginatorElement(): void
    {
        $this->loadPlugins(['Cake/Essentials']);

        $Request = new ServerRequest([
            'url' => '/',
            'params' => [
                'plugin' => null,
                'controller' => 'Articles',
                'action' => 'index',
            ],
        ]);

        $Paginated = new PaginatedResultSet(new ResultSet([]), [
            'alias' => 'Articles',
            'currentPage' => 1,
            'count' => 20,
            'totalCount' => 52,
            'hasPrevPage' => false,
            'hasNextPage' => true,
            'pageCount' => 3,
            'start' => 1,
            'end' => 52,
        ]);

        $builder = Router::createRouteBuilder('/');
        $builder->connect('/', ['controller' => 'Articles', 'action' => 'index']);
        Router::setRequest($Request);

        $View = new AppView(request: $Request, viewOptions: ['viewVars' => ['articles' => $Paginated]]);
        $result = $View->element('Cake/Essentials.paginator');

        $this->assertStringContainsString('<a class="page-link" tabindex="-1" aria-disabled="true" aria-label="Previous"><span aria-hidden="true">‹</span></a>', $result);
        $this->assertStringContainsString('<a class="page-link" href="/?page=2">2</a>', $result);
        $this->assertStringContainsString('<a class="page-link" href="/?page=3">3</a>', $result);
        $this->assertStringContainsString('<a class="page-link" rel="next" aria-label="Next" href="/?page=2"><span aria-hidden="true">›</span></a>', $result);
        $this->assertStringContainsString('Page 1 of 3, showing 20 items out of 52 total', $result);
    }
}

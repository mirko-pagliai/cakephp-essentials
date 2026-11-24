<?php
declare(strict_types=1);

namespace Cake\Essentials\Test\TestCase\Templates\Element;

use App\View\AppView;
use Cake\Datasource\Paging\PaginatedResultSet;
use Cake\Http\ServerRequest;
use Cake\ORM\ResultSet;
use Cake\Routing\Router;
use Cake\TestSuite\TestCase;
use PHPUnit\Framework\Attributes\CoversNothing;
use PHPUnit\Framework\Attributes\Test;

/**
 * PaginatorElementTest.
 *
 * @link templates/element/paginator.php
 */
#[CoversNothing]
class PaginatorElementTest extends TestCase
{
    /**
     * Renders the `paginator` element with the given view variables.
     *
     * @param array<string, mixed> $viewVars
     * @return string
     */
    private function renderElement(array $viewVars = []): string
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

        $builder = Router::createRouteBuilder('/');
        $builder->connect('/', ['controller' => 'Articles', 'action' => 'index']);
        Router::setRequest($Request);

        $View = new AppView(request: $Request, viewOptions: ['viewVars' => $viewVars]);

        return $View->element('Cake/Essentials.paginator');
    }

    /**
     * Tests the element on the first of three pages.
     */
    #[Test]
    public function testPaginatorElement(): void
    {
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

        $result = $this->renderElement(['articles' => $Paginated]);

        $this->assertStringContainsString('<a class="page-link" tabindex="-1" aria-disabled="true" aria-label="Previous"><span aria-hidden="true">‹</span></a>', $result);
        $this->assertStringContainsString('<a class="page-link" href="/?page=2">2</a>', $result);
        $this->assertStringContainsString('<a class="page-link" href="/?page=3">3</a>', $result);
        $this->assertStringContainsString('<a class="page-link" rel="next" aria-label="Next" href="/?page=2"><span aria-hidden="true">›</span></a>', $result);
        $this->assertStringContainsString('Page 1 of 3, showing 20 items out of 52 total', $result);
    }

    /**
     * Tests the element on a page that has both a previous and a next page.
     *
     * The previous and next links are rendered twice: in the full version of the links and in the compact one.
     */
    #[Test]
    public function testPaginatorElementOnMiddlePage(): void
    {
        $Paginated = new PaginatedResultSet(new ResultSet([]), [
            'alias' => 'Articles',
            'currentPage' => 2,
            'count' => 20,
            'totalCount' => 52,
            'hasPrevPage' => true,
            'hasNextPage' => true,
            'pageCount' => 3,
            'start' => 21,
            'end' => 40,
        ]);

        $result = $this->renderElement(['articles' => $Paginated]);

        $this->assertSame(2, substr_count($result, 'rel="prev"'));
        $this->assertSame(2, substr_count($result, 'rel="next"'));
        $this->assertStringContainsString('>Page 2 of 3</span>', $result);
        $this->assertStringContainsString('Page 2 of 3, showing 20 items out of 52 total', $result);
    }

    /**
     * Tests the element with a single page: the links are not rendered, the counter is.
     */
    #[Test]
    public function testPaginatorElementWithSinglePage(): void
    {
        $Paginated = new PaginatedResultSet(new ResultSet([]), [
            'alias' => 'Articles',
            'currentPage' => 1,
            'count' => 10,
            'totalCount' => 10,
            'hasPrevPage' => false,
            'hasNextPage' => false,
            'pageCount' => 1,
            'start' => 1,
            'end' => 10,
        ]);

        $result = $this->renderElement(['articles' => $Paginated]);

        $this->assertStringNotContainsString('<nav', $result);
        $this->assertStringContainsString('Page 1 of 1, showing 10 items out of 10 total', $result);
    }

    /**
     * Tests the element when the view has no paginated result set: nothing is rendered.
     */
    #[Test]
    public function testPaginatorElementWithoutPaginatedResultSet(): void
    {
        $this->assertSame('', $this->renderElement());
    }
}

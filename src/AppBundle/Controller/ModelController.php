<?php


namespace AppBundle\Controller;


use AppBundle\Entity\Model;
use AppBundle\Form\ModelType;
use AppBundle\Service\Printers\ModelServiceInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ModelController extends Controller
{
    /**
     * @var ModelServiceInterface
     */
    private $modelService;

    public function __construct(ModelServiceInterface $modelService)
    {
        $this->modelService = $modelService;
    }

    /**
     * @param Request $request
     *
     * @Route("model/add", name="add_model", methods={"GET"})
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     *
     * @return RedirectResponse|Response
     */
    public function create(Request $request)
    {
        $model = new Model();
        $form = $this->createFormAndHandler($request, $model);

        return $this->render('printer/model/add.html.twig',
            [
                'form' => $form->createView(),
            ]);

    }


    /**
     * @param Request $request
     *
     * @return RedirectResponse|Response
     * @Route("model/add", methods={"POST"})
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     */
    public function createProcess(Request $request)
    {
        $model = new Model();
        $form = $this->createFormAndHandler($request, $model);
        $this->modelService->add($model,$form);

        return $this->redirectToRoute(
            'model_view', ['id' => $model->getId()]
        );
    }

    /**
     * @Route("/model/edit/{id}", name="model_edit", methods={"GET"})
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     *
     * @param $id
     * @param Request $request
     * @return Response
     * @throws \Exception
     */
    public function editModel($id, Request $request)
    {
        $model = $this->modelService->findOneByID($id);

        if ($model === null) {
            $this->redirectToRoute("homepage");
        }

        $form = $this->createFormAndHandler($request, $model);

        return $this->render('printer/model/edit.html.twig',
            [
                'model' => $model,
                'form' => $form->createView(),
            ]);
    }
    
    /**
     * @Route("/model/edit/{id}", methods={"POST"})
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     *
     * @param $id
     * @param Request $request
     * @return Response
     * @throws \Exception
     */
    public function editModelProcess($id, Request $request)
    {
        $model = $this->modelService->findOneByID($id);

        $form = $this->createFormAndHandler($request, $model);

        $this->modelService->edit($model, $form);

        return $this->redirectToRoute("model_view",
            [
                'id' => $model->getId()
            ]);
    }

    /**
     * @param int $id
     * @return RedirectResponse
     * @Route("model/delete/{id}",name="model_delete", methods={"GET"})
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     */

    public function delete(int $id)
    {
        $this->modelService->delete($id);

        return $this->redirectToRoute(
            'homepage', []
        );

    }
    
    /**
     * @Route("/model/{id}",name="model_view" , methods={"GET"})
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     *
     * @param $id
     * @return Response
     */
    public function viewModel($id)
    {
        $model = $this->modelService->findOneByID($id);
        if (null === $model) {
            return $this->redirectToRoute("homepage");
        }

        return $this->render('printer/model/view.html.twig',
            [
                'model' => $model,
            ]);
    }

    /**
     * @param Request $request
     * @param Model $model
     * @return FormInterface
     */
    public function createFormAndHandler(Request $request, Model $model)
    {
        $form = $this->createForm(ModelType::class, $model);
        $form->handleRequest($request);
        return $form;
    }
}

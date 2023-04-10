<?php

namespace App\Http\Controllers\Bundle\Api\Product;

use App\Bundle\Common\Constants\MessageConst;
use App\Bundle\Common\Domain\Model\InvalidArgumentException;
use App\Bundle\ProductBundle\Application\ProductPostApplicationService;
use App\Bundle\ProductBundle\Application\ProductPostCommand;
use App\Bundle\ProductBundle\Infrastructure\FeatureImagePathRepository;
use App\Bundle\ProductBundle\Infrastructure\ProductRepository;
use App\Http\Controllers\Bundle\Api\Common\BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProductAttributeValueController extends BaseController
{
    /**
     * @param Request $request request
     */
    public function createProductAttributevalue(Request $request)
    {
        $applicationService = new ProductPostApplicationService(
            new ProductRepository(),
            new FeatureImagePathRepository(),
        );

        $file = $request->file('file');
        if (!$file) {
            throw new InvalidArgumentException();
        }
        $file->hashName();
        $path = Storage::put('/public/'. Auth::id(), $file);
        $url = Storage::url($path);

        $isAvatar = true;

        $command = new ProductPostCommand(
            $request->name,
            $request->code,
            $request->description,
            $request->category_id,
            $url,
            $isAvatar
        );

        $result = $applicationService->handle($command);
        $data = [
            'id' => $result->productId,
        ];

        return response()->json(['data' => $data], 200);
    }
}

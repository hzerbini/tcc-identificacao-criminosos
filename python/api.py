from flask import Flask, request
from flask_restful import Resource, Api
import chainer
from chainercv import utils
from chainercv.datasets import voc_bbox_label_names
from chainercv.links import SSD300
from chainercv.visualizations import vis_bbox
chainer.config.train = False
model = SSD300(
    n_fg_class=len(voc_bbox_label_names), pretrained_model='voc0712')
model.score_thresh = 0.30
chainer.serializers.load_npz('./model', model)

app = Flask(__name__)
api = Api(app)

class ImageRecognition(Resource):
    def get(self):
        img_path = utils.cached_download(request.args.get("url"))
        img = utils.read_image(img_path, color=True)
        bboxes, labels, scores = model.predict([img])

        mapped_labels = list(map( lambda x: voc_bbox_label_names[x], labels[0]))

        return {"data": mapped_labels}

api.add_resource(ImageRecognition, '/')

if __name__ == '__main__':
    app.run(host="0.0.0.0", port=5000, debug=True)
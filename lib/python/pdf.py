from PyPDF2 import PdfFileReader, PdfFileWriter
import logging
import sys
def split_pdf(infn, outfn):
    logging.info("开始拆分PDF")
    pdf_output = PdfFileWriter()
    pdf_input = PdfFileReader(open(infn, 'rb'))
    # 获取 pdf 共用多少页
    page_count = pdf_input.getNumPages()
    # 获取需要拆分多少页
    pages = int(sys.argv[3])
    # 如果总页数不够拆分，则按总页数进行拆分
    if pages > page_count:
        pages = page_count
    for i in range(0, pages):
        pdf_output.addPage(pdf_input.getPage(i))
    pdf_output.write(open(outfn, 'wb'))
    logging.info("结束PDF拆分")
def merge_pdf(infnList, outfn):
    pdf_output = PdfFileWriter()
    for infn in infnList:
        pdf_input = PdfFileReader(open(infn, 'rb'))
        # 获取 pdf 共用多少页
        page_count = pdf_input.getNumPages()
        print(page_count)
        for i in range(page_count):
            pdf_output.addPage(pdf_input.getPage(i))
    pdf_output.write(open(outfn, 'wb'))

logging.basicConfig(filename='C:\\users\\fuhao\\desktop\\logger.log', level=logging.INFO)

if __name__ == '__main__':   
    infn = sys.argv[1]
    outfn = sys.argv[2]
    split_pdf(infn, outfn)
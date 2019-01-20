using System.Collections;
using System.Collections.Generic;
using UnityEngine;
using UnityEngine.EventSystems;
using UnityEngine.UI;
public class Item : MonoBehaviour, IPointerDownHandler {
    private bool dragging = false;
    private float distance;

	PointerEventData m_PointerEventData;
	GraphicRaycaster m_Raycaster;
	EventSystem m_EventSystem;

	Vector3 initPos;
   

	void Start()
	{
		m_EventSystem = GetComponent<EventSystem>();
		m_Raycaster = MayaMove.instance.GetComponent<InventoryTab>().InventoryCanvas.GetComponent<GraphicRaycaster>();
	}
    public void OnPointerDown (PointerEventData eventData) 
    {
		if (Input.GetMouseButton(0))
		{
			initPos = transform.position;
        	dragging = true;
		}
    }
 
	void ThrowAway()
	{
			GameObject tmp = Instantiate(SpritePool.instance.itemInGame, MayaMove.instance.transform.position , Quaternion.Euler(Vector3.right *90f));					
			ItemStats t1 = GetComponent<ItemStats>();
			ItemStats t2 = tmp.GetComponent<ItemStats>();
			t2.damage = t1.damage;
			t2.attackSpeed = t1.attackSpeed;
			t2.type = t1.type;
			tmp.GetComponent<SpriteRenderer>().sprite = t1.GetComponent<Image>().sprite;
			Destroy(this.gameObject);
	}
    void Update()
    {
		if (dragging && !Input.GetMouseButton(0))
		{
			m_PointerEventData = new PointerEventData(m_EventSystem);
			m_PointerEventData.position = Input.mousePosition;
			List<RaycastResult> results = new List<RaycastResult>();
			dragging = false;
			m_Raycaster.Raycast(m_PointerEventData, results);
			if (results.Count != 0)
			{
				ItemStats stat ;

				if (transform.parent.gameObject.tag == "equipCase")
				{
					stat = GetComponent<ItemStats>();
					MayaMove.instance.minDamage -= stat.damage;
					MayaMove.instance.maxDamage -= stat.damage;
					MayaMove.instance.fireRate -= stat.attackSpeed;
				}

				foreach (RaycastResult result in results)
				{
					if (result.gameObject.tag == "inventaireCase" || result.gameObject.tag == "equipCase")
					{
						Transform toSwtich = null;
						if (result.gameObject.transform.childCount != 0)
						{
							toSwtich = result.gameObject.transform.GetChild(0);
							toSwtich.SetParent(this.transform.parent);
							toSwtich.transform.position = initPos;

							if (transform.parent.gameObject.tag == "equipCase")
							{
								stat = toSwtich.GetComponent<ItemStats>();
								MayaMove.instance.minDamage += stat.damage;
								MayaMove.instance.maxDamage += stat.damage;
								MayaMove.instance.fireRate += stat.attackSpeed;
							}
						}

						transform.position = result.gameObject.transform.position;
						transform.SetParent(result.gameObject.transform);

						if (result.gameObject.tag == "equipCase")
						{
							if (toSwtich != null)
							{
								stat = toSwtich.GetComponent<ItemStats>();
								MayaMove.instance.minDamage -= stat.damage;
								MayaMove.instance.maxDamage -= stat.damage;
								MayaMove.instance.fireRate -= stat.attackSpeed;
							}

							stat = GetComponent<ItemStats>();
							MayaMove.instance.minDamage += stat.damage;
							MayaMove.instance.maxDamage += stat.damage;
							MayaMove.instance.fireRate += stat.attackSpeed;
						}
						return;
					}
				}
				ThrowAway();
			}
			else
			{
				ThrowAway();
			}

		}
        else if (dragging)
        {
            // Ray ray = Camera.main.ScreenPointToRay(Input.mousePosition);
            // Vector3 rayPoint = ray.GetPoint(distance);
            transform.position = Input.mousePosition;
        }

    }
}

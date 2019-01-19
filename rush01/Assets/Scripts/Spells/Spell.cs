using System.Collections;
using System.Collections.Generic;
using UnityEngine;

public class Spell : MonoBehaviour {

	[SerializeField] ParticleSystem particle;
	[SerializeField] Transform indicator;
	
	[HideInInspector] public float damage = 100f;
	[HideInInspector] public float manaCost = 30f;
	bool end = true;

	public void activeSpell()
	{
		if (MayaMove.instance.Mana < manaCost)
			return;

		MayaMove.instance.Mana -= manaCost;
		end = false;
		indicator.gameObject.SetActive(true);
	}


	public void addSpell(int c)
	{
		if (c == 1)
		{
			SpellsTab.instance.spell1.RemoveAllListeners();
			SpellsTab.instance.spell1.AddListener(activeSpell);
		}
		if (c == 2)
		{
			SpellsTab.instance.spell2.RemoveAllListeners();
			SpellsTab.instance.spell2.AddListener(activeSpell);	
		}
		if (c == 3)
		{
			SpellsTab.instance.spell3.RemoveAllListeners();
			SpellsTab.instance.spell3.AddListener(activeSpell);
		}
		if (c == 4)
		{
			SpellsTab.instance.spell4.RemoveAllListeners();
			SpellsTab.instance.spell4.AddListener(activeSpell);
		}
	}

	void Update () {
		if (end)
			return;

		Ray ray;
		RaycastHit hit;
		ray = Camera.main.ScreenPointToRay(Input.mousePosition);
		if(Physics.Raycast(ray, out hit, Mathf.Infinity, 1<<8)){
			indicator.position = hit.point+ 0.5f*Vector3.up;

			if (Input.GetMouseButtonDown(0))
			{
				Collider[] hitColliders = Physics.OverlapSphere(hit.point, 3, 1<<9);
				int i = 0;
				while (i < hitColliders.Length)
				{
					hitColliders[i].GetComponent<ZombieMove>().TakeDmg(damage); //takeDamage
					i++;
				}
				end = true;
				indicator.gameObject.SetActive(false);
				particle.transform.position = hit.point - 0.5f * Vector3.up;
				particle.Play();
				// Invoke("disableWithDelay", 1f);
				end = true;


			}
		}
	}

	void disableWithDelay()
	{
		this.gameObject.SetActive(false);
	}
}
